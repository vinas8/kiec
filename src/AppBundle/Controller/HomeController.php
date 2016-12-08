<?php

namespace AppBundle\Controller;

use AppBundle\Exception\LessonException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        try {
            $response = $this->forward('AppBundle:Lesson:current');
        } catch (LessonException $e) {
            $this->addFlash('info', $e->getMessage());
            $response = $this->forward('AppBundle:Class:view');
        }

        return $response;
    }
}
