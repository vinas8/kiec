<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Route("/class/{id}", name="class")
     */
    public function classAction($id)
    {
        $class = $this->getDoctrine()->getRepository('AppBundle:ClassInfo')
            ->findClassesByTeacherAndClassId($this->getTeacher(), $id);
        return $this->render('@App/Class/class.html.twig', array(
            'class' => $class
        ));
    }

    private function getTeacher()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }
}
