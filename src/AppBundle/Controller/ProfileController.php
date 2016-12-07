<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\StudentInfo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends Controller
{
    /**
     * @Route("/profile/{studentInfo}", name="profile")
     */
    public function profileAction(StudentInfo $studentInfo = null)
    {
        if (!$studentInfo) {
            throw new NotFoundHttpException("Mokinys nerastas.");
        }
        if (!$studentInfo->getClassInfo()->getUser()->contains($this->getCurrentUser())) {
            throw new AccessDeniedException("Mokinys nepasiekiamas.");
        }

        $activityService = $this->get('app.activity');
        $resultService = $this->get('app.result');

        $activities = $activityService->getActivityList();
        $bestResults = $resultService->getBestResultsByStudent($studentInfo);
        $allResults = $resultService->getResultListByStudent($studentInfo);

        return $this->render(
            'AppBundle:Profile:profile.html.twig',
            [
            "student" => $studentInfo,
            "activities" => $activities,
            "bestResults" => $bestResults,
            "allResults" => $allResults
            ]
        );
    }

    /**
     * @return User
     */
    private function getCurrentUser()
    {
        return $this->get('app.current_user_data_service')->getUser();
    }
}
