<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\Result;
use AppBundle\Exception\LessonException;
use AppBundle\Form\ActivityResultSetType;
use AppBundle\Utils\ResultSet;
use AppBundle\Utils\ActivityResultSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class LessonController extends Controller
{
    /**
     * @Route("/current", name="current")
     */
    public function currentAction(Request $request)
    {
        try {
            $currentLesson = $this->get('app.lesson_service')->getCurrentLesson();
            return $this->display($currentLesson, 'Dabartinė pamoka', $request);
        } catch (LessonException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->render('@App/Lesson/errors.html.twig');
        }
    }

    /**
     * @Route("/lesson/{Lesson}", name="lesson")
     */
    public function lessonAction(Lesson $Lesson = null, Request $request)
    {
        if (!$Lesson) {
            $this->addFlash('danger', 'Tokia pamoka neegzistuoja!');
            return $this->redirectToRoute('homepage');
        }

        return $this->display($Lesson, 'Pamoka', $request);
    }

    /**
     * @param  Lesson  $lesson
     * @param  string  $title
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function display(Lesson $lesson, $title, $request)
    {
        $classInfo = $lesson->getClassInfo();

        $students = $this->get('app.student_info')->getStudentListByClass($classInfo);
        $activities = $this->get('app.activity')->getActivityList();
        $results = $this->get('app.result')->getLastResultsByClass($classInfo);
        $nextLesson = $this->get('app.lesson_service')->getNext($lesson);
        $prevLesson = $this->get('app.lesson_service')->getPrev($lesson);
        $activityResultSet = new ActivityResultSet();
        foreach ($activities as $activity) {
            $resultSet = new ResultSet();
            $activityResultSet->getActivities()->add($resultSet);
            foreach ($students as $student) {
                $result = new Result($activity, $student);
                $resultSet->getResults()->add($result);
            }
        }
        $form = $this->createForm(ActivityResultSetType::class, $activityResultSet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.result')->addNewResults($form->getData());
            $this->addFlash('success', "Įrašyti nauji rezultatai.");
            return $this->redirect($request->headers->get('referer'));
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
}
