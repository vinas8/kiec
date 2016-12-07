<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\Result;
use AppBundle\Exception\LessonException;
use AppBundle\Form\ActivityResultSetType;
use AppBundle\Service\LessonService;
use AppBundle\Utils\ResultSet;
use AppBundle\Utils\ActivityResultSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LessonController extends Controller
{
    /**
     * @Route("/current", name="current")
     *
     * @param  Request $request
     * @return Response
     */
    public function currentAction(Request $request)
    {
        try {
            $currentLesson = $this->lessonService()->getCurrentLesson($this->getCurrentUser());
            return $this->display($currentLesson, 'Dabartinė pamoka', $request);
        } catch (LessonException $e) {
            $this->addFlash('info', $e->getMessage());
            return $this->redirectToRoute('classes');
        }
    }

    /**
     * @Route("/lesson/{lesson}", name="lesson")
     *
     * @param  Request $request
     * @param  Lesson $lesson
     * @return Response
     */
    public function lessonAction(Request $request, Lesson $lesson = null)
    {
        if (!$lesson) {
            $this->addFlash('danger', 'Tokia pamoka neegzistuoja!');
            return $this->redirectToRoute('homepage');
        }

        return $this->display($lesson, 'Pamoka', $request);
    }

    /**
     * @param  Lesson  $lesson
     * @param  string  $title
     * @param  Request $request
     * @return Response
     */
    private function display(Lesson $lesson, $title, $request)
    {
        $classInfo = $lesson->getClassInfo();

        $user = $this->getCurrentUser();

        $students = $this->get('app.student_info')->getStudentListByClass($classInfo);
        $activities = $this->get('app.activity')->getActivityList();
        $results = $this->get('app.result')->getLastResultsByClass($classInfo);
        $nextLesson = $this->get('app.lesson_service')->getNext($user, $lesson);
        $prevLesson = $this->get('app.lesson_service')->getPrev($user, $lesson);
        $activityResultSet = new ActivityResultSet();
        foreach ($activities as $activity) {
            $resultSet = new ResultSet();
            $activityResultSet->getActivities()->add($resultSet);
            foreach ($students as $student) {
                $result = new Result($activity, $student, $this->get('security.token_storage')->getToken()->getUser(), $this->get('app.time_service'));
                $resultSet->getResults()->add($result);
            }
        }
        $form = $this->createForm(ActivityResultSetType::class, $activityResultSet);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->get('app.result')->addNewResults($form->getData());
                $this->addFlash('success', "Įrašyti nauji rezultatai.");
                return $this->redirect($request->headers->get('referer'));
            } else {
                $this->addFlash('danger', "Neteisingai įvesti duomenys.");
                return $this->redirect($request->headers->get('referer'));
            }
        }


        return $this->render(
            'AppBundle:Lesson:lesson.html.twig',
            [
            'title' => $title,
            'lesson' => $lesson,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson,
            'students' => $students,
            'activities' => $activities,
            'results' => $results,
            'form' => $form->createView()
            ]
        );
    }

    /**
     * @return mixed
     */
    private function getCurrentUser()
    {
        return $this->get('app.current_user_data_service')->getUser();
    }

    /**
     * @return LessonService
     */
    private function lessonService()
    {
        return $this->get('app.lesson_service');
    }
}
