<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\StudentInfo;

class ProfileController extends Controller
{
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

        return $this->render(
            'AppBundle:Profile:profile.html.twig', [
            "student" => $studentInfo,
            "activities" => $activities,
            "bestResults" => $bestResults,
            "allResults" => $allResults
            ]
        );
    }

    /**
     * @Route("/teacher-profile", name="teacher_profile")
     */
    public function viewTeacherProfileAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('@App/Profile/teacher.html.twig', array('teacher' => $user));
    }
}
