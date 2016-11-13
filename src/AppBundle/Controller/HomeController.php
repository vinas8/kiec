<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Home:index.html.twig', []);
    }

    /**
     * @Route("/lesson/view/{class}", name="lesson_view")
     */
    public function viewAction(ClassInfo $class = null)
    {
        $activityService = $this->get('app.activity');
        $resultService = $this->get('app.result');
        $students = $class->getStudents();
        $activities = $activityService->getActivityList();
        $results = $resultService->getLastResultsByClass($class);

        return $this->render('AppBundle:Home:view.html.twig', ["students" => $students, "activities" => $activities, "results" => $results]);
    }


}
