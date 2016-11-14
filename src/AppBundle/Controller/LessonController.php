<?php
namespace AppBundle\Controller;

use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LessonController extends Controller
{
    /**
     * @Route("/lesson/{id}", name="lesson")
     */
    public function lessonAction($id)
    {
        $lessonService = $this->get('app.lesson_service');

        try {
            $lesson = $lessonService->getLesson($id);
        } catch (NoResultException $e) {
            return $this->render('AppBundle:Lesson:error.html.twig', [
                'message' => 'Pamoka nerasta!'
            ]);
        }

        try {
            $nextLesson = $lessonService->getNext($id);
        } catch (NoResultException $e) {
            $nextLesson = false;
        }

        try {
            $prevLesson = $lessonService->getPrev($id);
        } catch (NoResultException $e) {
            $prevLesson = false;
        }

        return $this->render('AppBundle:Lesson:lesson.html.twig', [
            'title'      => 'Pamoka',
            'lesson'     => $lesson,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson
        ]);
    }

}
