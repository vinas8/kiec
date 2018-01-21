<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StudentInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function profileAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('@App/User/profile.html.twig', array('user' => $user));
    }

}
