<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Exception\LessonException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LessonController extends Controller
{
    /**
     * @Route("/current", name="current")
     */
    public function currentAction()
    {
        try {
            $currentLesson = $this->get('app.lesson_service')->getCurrentLesson();
            return $this->display($currentLesson, 'Dabartinė pamoka');
        }
        catch (LessonException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->render('@App/Lesson/errors.html.twig');
        }
    }

    /**
     * @Route("/lesson/{Lesson}", name="lesson")
     */
    public function lessonAction(Lesson $Lesson = null)
    {
        if (!$Lesson) {
            $this->addFlash('danger', 'Tokia pamoka neegzistuoja!');
            return $this->redirectToRoute('homepage');
        }

        return $this->display($Lesson, 'Pamoka');
    }

    /**
     * @param  Lesson $lesson
     * @param  string $title
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function display(Lesson $lesson, $title)
    {
        $classInfo  = $lesson->getClassInfo();

        $students   = $this->get('app.student_info')->getStudentListByClass($classInfo);
        $activities = $this->get('app.activity')->getActivityList();
        $results    = $this->get('app.result')->getLastResultsByClass($classInfo);
        $nextLesson = $this->get('app.lesson_service')->getNext($lesson);
        $prevLesson = $this->get('app.lesson_service')->getPrev($lesson);

        return $this->render('AppBundle:Lesson:lesson.html.twig', [
            'title'      => $title,
            'lesson'     => $lesson,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson,
            'students'   => $students,
            'activities' => $activities,
            'results'    => $results
        ]);
    }

}
