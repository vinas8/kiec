<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $limit  = $request->query->get('limit', self::DEFAULT_SCHEDULE_LIMIT);

        $user = $this->get('app.current_user_data_service')->getUser();

        $lessons = $this->lessonService()->getUserLessonsFromNow($user, $offset, $limit);
        $hasMore = count($this->lessonService()->getUserLessonsFromNow($user, $offset + $limit, $limit)) > 0;

        $results = [
            'collection' => [],
            'next_href'  => $this->generateUrl('api_schedule', [
                'offset' => $offset + $limit,
                'limit'  => $limit
            ])
        ];

        if (!$hasMore) {
            unset($results['next_href']);
        }

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
     * @return \AppBundle\Service\LessonService
     */
    private function lessonService()
    {
        return $this->get('app.lesson_service');
    }
}
