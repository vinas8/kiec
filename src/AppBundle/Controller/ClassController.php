<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ClassController extends Controller
{
    /**
     * @Route("/classes", name="classes")
     */
    public function classesAction()
    {
        $classes = $this->getDoctrine()->getRepository('AppBundle:ClassInfo')
            ->findClassesByTeacher($this->getTeacher());
        return $this->render('@App/Class/classes.html.twig', array(
            'classes' => $classes
        ));
    }

    /**
     * @Route("/class/{name}", name="class")
     */
    public function classAction($name)
    {
        $class = $this->getDoctrine()->getRepository('AppBundle:ClassInfo')
            ->findClassesByTeacherAndClassNameg($this->getTeacher(),$name);
        return $this->render('@App/Class/class.html.twig', array(
            'class' => $class
        ));
    }

    private function getTeacher()
    {
        return $this->get('security.token_storage')->getToken()->getUser()->getTeacherInfo();
    }
}
