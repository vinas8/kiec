<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ScheduleController extends Controller
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Schedule:index.html.twig');
    }
}
