<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Service\LessonService;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LessonController extends Controller implements InitializableController
{
    /**
     * @var LessonService
     */
    private $lessonService;

    /**
     * Initializes controller
     */
    public function init()
    {
        $this->lessonService = $this->get('app.lesson_service');
    }

    /**
     * @Route("/current", name="current")
     */
    public function currentAction()
    {
        $lesson = $this->lessonService->getCurrentLesson();

        return $this->display($lesson, 'Dabartinė pamoka');
    }

    /**
     * @Route("/lesson/{id}", name="lesson")
     */
    public function lessonAction($id)
    {
        try {
            $lesson = $this->lessonService->getLesson($id);
        } catch (NoResultException $e) {
            return $this->render('AppBundle:Lesson:error.html.twig', [
                'message' => 'Pamoka nerasta!'
            ]);
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
            $nextLesson = $this->lessonService->getNext($id);
        } catch (NoResultException $e) {
            $nextLesson = false;
        }

        try {
            $prevLesson = $this->lessonService->getPrev($id);
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
