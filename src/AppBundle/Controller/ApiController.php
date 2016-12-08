<?php
namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;
use AppBundle\Service\ClassInfoService;
use AppBundle\Service\LessonService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    const DEFAULT_SCHEDULE_OFFSET = 0;
    const DEFAULT_SCHEDULE_LIMIT = 10;

    /**
     * @Route("/api/schedule", name="api_schedule")
     *
     * @param  Request $request
     * @return Response
     */
    public function scheduleAction(Request $request)
    {
        $offset = $request->query->get('offset', self::DEFAULT_SCHEDULE_OFFSET);
        $limit = $request->query->get('limit', self::DEFAULT_SCHEDULE_LIMIT);

        $user = $this->getCurrentUser();

        $lessons = $this->lessonService()->getUserLessonsFromNow($user, $offset, $limit);
        $hasMore = $this->lessonService()->hasUserLessonsFromNow($user, $offset + $limit, $limit);

        $results = [
            'collection' => [],
            'next_href' => $hasMore ? $this->generateUrl('api_schedule', [
                'offset' => $offset + $limit,
                'limit' => $limit
            ]) : null
        ];

        /**
         * @var Lesson $lesson
         */
        foreach ($lessons as $lesson) {
            $results['collection'][] = [
                'id' => $lesson->getId(),
                'group' => $lesson->getClassInfo()->getName(),
                'startTime' => $lesson->getStartTime()->format('Y-m-d H:i'),
                'endTime' => $lesson->getEndTime()->format('Y-m-d H:i')
            ];
        }

        return $this->json($results);
    }

    /**
     * @Route("/api/schedule/add", name="api_schedule_add")
     *
     * @param  Request $request
     * @return Response
     */
    public function scheduleAddAction(Request $request)
    {
        if (!$request->isMethod('POST')) {
            return $this->scheduleAddErrorResponse('Netinkama užklausa!');
        }

        $type = $request->get('type');
        if ($type !== 'single' && $type !== 'multiple') {
            return $this->scheduleAddErrorResponse('Nepavyko nustatyti ar planuojate vieną, ar kelias pamokas!');
        }

        $user = $this->getCurrentUser();

        $classinfo = $this->classInfoService()->getUserClassById($user, $request->get('class'));
        if ($classinfo === null) {
            return $this->scheduleAddErrorResponse('Neturite tokios klasės!');
        }

        return $type === 'single' ?
            $this->handleSingleSchedule($user, $classinfo, $request) :
            $this->handleMultipleSchedule($user, $classinfo, $request);
    }

    /**
     * Handles single lesson creation
     *
     * @param  User $user
     * @param  ClassInfo $classinfo
     * @param  Request $request
     * @return JsonResponse
     */
    private function handleSingleSchedule(User $user, ClassInfo $classinfo, Request $request)
    {
        $date = $request->get('single_date');
        $start = $request->get('single_start_time');
        $end = $request->get('single_end_time');

        if ($date === null || $start === null || $end === null) {
            return $this->scheduleAddErrorResponse('Neužpildėte visų duomenų!');
        }

        $start_time = \DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $start);
        $end_time = \DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $end);

        if (!$start_time && !$end_time) {
            return $this->scheduleAddErrorResponse('Neteisingai nurodėte datą/laiką!');
        }

        if ($start_time >= $end_time) {
            return $this->scheduleAddErrorResponse('Pradžios laikas negali būti didesnis nei pabaigos!');
        }

        if ($this->lessonService()->hasUserLessonAt($user, $start_time, $end_time)) {
            return $this->scheduleAddErrorResponse('Nurodytu laiku jau turite pamoką!');
        }

        $em = $this->getDoctrine()->getEntityManager();

        try {
            $em->persist($this->createLesson($user, $classinfo, $start_time, $end_time));
            $em->flush();

            return $this->scheduleAddSuccessResponse('Sėkmingai suplanuota pamoka!');
        } catch (\Exception $e) {
            return $this->scheduleAddErrorResponse('Nepavyko įrašyti pamokos!');
        }
    }

    /**
     * Handles multiple lesson creation
     *
     * @param  User $user
     * @param  ClassInfo $classinfo
     * @param  Request $request
     * @return JsonResponse
     */
    private function handleMultipleSchedule(User $user, ClassInfo $classinfo, Request $request)
    {
        $start_date = $request->get('multiple_start_date');
        $end_date = $request->get('multiple_end_date');
        $weekdays = $request->get('weekdays');
        $start_time = $request->get('multiple_start_time');
        $end_time = $request->get('multiple_end_time');

        if ($start_date === null ||
            $end_date === null ||
            $weekdays === null ||
            $start_time === null ||
            $end_time === null ||
            !is_array($weekdays)
        ) {
            return $this->scheduleAddErrorResponse('Neužpildėte visų duomenų!');
        }

        $start_date = \DateTime::createFromFormat('Y-m-d', $start_date);
        $end_date = \DateTime::createFromFormat('Y-m-d', $end_date);

        if (!$start_date && !$end_date) {
            return $this->scheduleAddErrorResponse('Nurodėte neteisingas datas!');
        }

        if ($start_date >= $end_date) {
            return $this->scheduleAddErrorResponse('Pradžios data negali būti didesnė nei pabaigos data!');
        }

        $start_time = \DateTime::createFromFormat('H:i', $start_time);
        $end_time = \DateTime::createFromFormat('H:i', $end_time);

        if (!$start_time && !$end_time) {
            return $this->scheduleAddErrorResponse('Nurodėte neteisingus laikus!');
        }

        if ($start_time >= $end_time) {
            return $this->scheduleAddErrorResponse('Pradžios laikas negali būti didesnis nei pabaigos laikas!');
        }

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($start_date, $interval, $end_date);

        $em = $this->getDoctrine()->getEntityManager();
        $em->beginTransaction();

        try {
            /**
             * @var \DateTime $dt
             */
            foreach ($period as $dt) {
                $day = $dt->format('Y-m-d');

                if (!in_array($dt->format('N'), $weekdays))
                    continue;

                $start_time = \DateTime::createFromFormat('Y-m-d H:i', $day . ' ' . $start_time->format('H:i'));
                $end_time = \DateTime::createFromFormat('Y-m-d H:i', $day . ' ' . $end_time->format('H:i'));

                if ($this->lessonService()->hasUserLessonAt($user, $start_time, $end_time)) {
                    return $this->scheduleAddErrorResponse('Nurodytu laiku jau turite pamoką!');
                }

                $em->persist($this->createLesson($user, $classinfo, $start_time, $end_time));
                $em->flush();
            }

            $em->commit();

            return $this->scheduleAddSuccessResponse('Sėkmingai suplanuotos pamokos!');
        } catch (\Exception $e) {
            $em->rollback();

            return $this->scheduleAddErrorResponse('Nepavyko įrašyti pamokų!');
        }
    }

    /**
     * Returns new lesson entity
     *
     * @param  User $user
     * @param  ClassInfo $class
     * @param  \DateTime $start_time
     * @param  \DateTime $end_time
     * @return Lesson
     */
    private function createLesson(User $user, ClassInfo $class, \DateTime $start_time, \DateTime $end_time)
    {
        $lesson = new Lesson();
        $lesson->setUser($user);
        $lesson->setStartTime($start_time);
        $lesson->setEndTime($end_time);
        $lesson->setClassInfo($class);

        return $lesson;
    }

    /**
     * Returns error message in json
     *
     * @param  string $message
     * @return JsonResponse
     */
    private function scheduleAddErrorResponse($message)
    {
        $this->addFlash('danger', $message);

        return $this->redirectToRoute('schedule');
    }

    /**
     * Returns success message in json
     *
     * @param  string $message
     * @return JsonResponse
     */
    private function scheduleAddSuccessResponse($message)
    {
        $this->addFlash('success', $message);

        return $this->redirectToRoute('schedule');
    }

    /**
     * @return LessonService
     */
    private function lessonService()
    {
        return $this->get('app.lesson_service');
    }

    /**
     * @return ClassInfoService
     */
    private function classInfoService()
    {
        return $this->get('app.class_info_service');
    }

    /**
     * @return User
     */
    private function getCurrentUser()
    {
        return $this->get('app.current_user_data_service')->getUser();
    }
}
