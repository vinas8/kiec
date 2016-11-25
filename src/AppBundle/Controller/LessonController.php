<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Exception\LessonException;
use Doctrine\ORM\NoResultException;
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
     * @Route("/lesson/{id}", name="lesson")
     */
    public function lessonAction($id)
    {
        try {
            $lesson = $this->get('app.lesson_service')->getLesson($id);
        } catch (NoResultException $e) {
            $this->addFlash('notice', "Pamoka nerasta");
            return $this->render('@App/Lesson/errors.html.twig');
        }

        return $this->display($lesson, 'Pamoka');
    }

    /**
     * @param  Lesson $lesson
     * @param  string $title
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function display($lesson, $title)
    {
        $id = $lesson->getId();

        try {
            $nextLesson = $this->get('app.lesson_service')->getNext($id);
        } catch (NoResultException $e) {
            $nextLesson = false;
        }

        try {
            $prevLesson = $this->get('app.lesson_service')->getPrev($id);
        } catch (NoResultException $e) {
            $prevLesson = false;
        }

        return $this->render('AppBundle:Lesson:lesson.html.twig', [
            'title'      => $title,
            'lesson'     => $lesson,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson
        ]);
    }

}
