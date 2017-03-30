<?php

namespace AppBundle\Controller;

use Proxies\__CG__\AppBundle\Entity\StudentInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function profileAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('@App/User/profile.html.twig', array('user' => $user));
    }

    /**
     * @Route("/user/set/teacher", name="user_set_teacher")
     */
    public function setTeacherAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ((!$user->hasRole("ROLE_TEACHER")) && (!$user->hasRole("ROLE_STUDENT")))
        {
            $user->addRole("ROLE_TEACHER");
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->forward("AppBundle:Home:index");
    }

    /**
     * @Route("/user/set/student", name="user_set_student")
     */
    public function setStudentAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ((!$user->hasRole("ROLE_TEACHER")) && (!$user->hasRole("ROLE_STUDENT")))
        {
            $em = $this->getDoctrine()->getManager();
            $studentInfo = new StudentInfo();
            $studentInfo->setName($user->getName());
            $em->persist($studentInfo);
            $em->flush();
            $user->addRole("ROLE_STUDENT");
            $user->setStudentInfo($studentInfo);
            $em->persist($user);
            $em->flush();
        }
        return $this->forward("AppBundle:Home:index");
    }
}
