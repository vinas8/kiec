<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
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
        return $this->render('AppBundle:Home:index.html.twig', []);
    }

    /**
     * @Route("/lesson/view/{classInfo}", name="lesson_view")
     */
    public function viewAction(ClassInfo $classInfo = null)
    {
        $activityService = $this->get('app.activity');
        $resultService = $this->get('app.result');
        $studentInfoService = $this->get('app.student_info');
        $students = $studentInfoService->getStudentListByClass($classInfo);
        $activities = $activityService->getActivityList();
        $results = $resultService->getLastResultsByClass($classInfo);

        return $this->render('AppBundle:Home:view.html.twig', ["students" => $students, "activities" => $activities, "results" => $results]);
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

        return $this->render('AppBundle:Home:profile.html.twig', ["student" => $studentInfo, "activities" => $activities,
        "bestResults" => $bestResults, "allResults" => $allResults]);
    }


}
