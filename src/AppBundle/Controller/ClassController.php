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
    public function classListAction()
    {

        $classes = $this->getDoctrine()->getRepository('AppBundle:ClassInfo')
            ->findClassesByTeacher($this->get('app.current_user_data_service')->getUser());
        return $this->render(
            '@App/Class/classes.html.twig', array(
            'classes' => $classes
            )
        );
    }

    /**
     * @Route("/class/{id}", name="class")
     */
    public function classAction($id)
    {
        $class = $this->getDoctrine()->getRepository('AppBundle:ClassInfo')
            ->findClassesByTeacherAndClassId($this->get('app.current_user_data_service')->getUser(), $id);
        return $this->render(
            '@App/Class/class.html.twig', array(
            'class' => $class
            )
        );
    }
}
