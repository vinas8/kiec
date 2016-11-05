<?php

namespace AppBundle\Controller;

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
     * @Route("/edit/{classId}", name="edit")
     */
    public function editAction($classId)
    {
        $studentInfoService = $this->get('app.student_info');
        $activityService = $this->get('app.activity');
        $resultService = $this->get('app.result');

        $activities = $activityService->getActivityList();
        $results = $resultService->getLastResultsByClass($classId);
        $students = $studentInfoService->getStudentListByClass($classId);

        return $this->render('AppBundle:Home:edit.html.twig', ["students" => $students, "activities" => $activities, "results" => $results]);
    }


}
