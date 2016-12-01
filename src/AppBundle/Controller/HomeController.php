<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StudentInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $auth = false;
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $auth = true;
        }

        return $this->render('AppBundle:Home:index.html.twig', ['auth' => $auth]);
    }

    /**
     * @Route("/profile/{studentInfo}", name="profile")
     */
    public function profileAction(StudentInfo $studentInfo = null)
    {
        $activityService = $this->get('app.activity');
        $resultService = $this->get('app.result');

        $activities = $activityService->getActivityList();
        $bestResults = $resultService->getBestResultsByStudent($studentInfo);
        $allResults = $resultService->getResultListByStudent($studentInfo);

        return $this->render('AppBundle:Home:profile.html.twig', [
            "student" => $studentInfo,
            "activities" => $activities,
            "bestResults" => $bestResults,
            "allResults" => $allResults
        ]);
    }


}
