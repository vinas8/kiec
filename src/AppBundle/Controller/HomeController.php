<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
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
     * @Route("/lesson/view/{class}", name="lesson_view")
     */
    public function editAction(ClassInfo $class)
    {
        $students = $class->getStudents();

        return $this->render('AppBundle:Home:edit.html.twig', ["students" => $students]);
    }


}
