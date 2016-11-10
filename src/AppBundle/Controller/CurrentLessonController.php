<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 16.11.7
 * Time: 12.26
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CurrentLessonController extends Controller
{
    /**
     * @Route("/rezultatu-ivedimas/dabartine-pamoka", name="current_lesson_input_result")
     */
    public function resultsAction()
    {
        $currentLesson = $this->get('app.lesson_service')->getCurrentLesson();
        return $this->render('@App/current_lesson/results.html.twig', [
            'currentLesson' => $currentLesson
        ]);
    }
}