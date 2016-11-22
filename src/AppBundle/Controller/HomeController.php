<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\Result;
use AppBundle\Entity\StudentInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ResultType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $auth = false;
        if( $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ){
            $auth = true;
        }
        return $this->render('AppBundle:Home:index.html.twig', ['auth' => $auth]);
    }

    /**
     * @Route("/lesson/view/{classInfo}", name="lesson_view")
     */
    public function viewAction(ClassInfo $classInfo = null, Request $request)
    {
        $activityService = $this->get('app.activity');
        $resultService = $this->get('app.result');
        $studentInfoService = $this->get('app.student_info');
        $students = $studentInfoService->getStudentListByClass($classInfo);
        $activities = $activityService->getActivityList();
        $results = $resultService->getLastResultsByClass($classInfo);
        $form = $this->createForm(ResultType::class, null, array("classInfo" => $classInfo));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newResults = $form->getData();
            $resultService->setResults($newResults);
        }

        return $this->render('AppBundle:Home:view.html.twig', ["students" => $students, "activities" => $activities, "results" => $results, "form" => $form->createView()]);
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
