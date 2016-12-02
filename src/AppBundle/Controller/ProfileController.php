<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends Controller
{
    /**
     * @Route("/teacher-profile", name="teacher_profile")
     */
    public function viewTeacherProfileAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('@App/Profile/teacher.html.twig', array('teacher' => $user));
    }
}
