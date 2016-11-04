<?php

namespace AppBundle\Controller;

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
     * @Route("/edit/{classId}", name="edit")
     */
    public function editAction($classId)
    {
        $studentsService = $this->get('app.student_info');

        $students = $studentsService->getStudentListByClass($classId);

        return $this->render('AppBundle:Home:edit.html.twig', ["students" => $students]);
    }


}
