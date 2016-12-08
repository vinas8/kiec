<?php
namespace AppBundle\Controller;

use AppBundle\Service\ClassInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ScheduleController extends Controller
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function indexAction()
    {
        $user = $this->get('app.current_user_data_service')->getUser();

        $has_classes = $this->classInfoService()->hasUserClasses($user);

        return $this->render('AppBundle:Schedule:index.html.twig', [
            'has_classes' => $has_classes,
            'classes' => $has_classes ? $this->classInfoService()->getUserClasses($user) : null
        ]);
    }

    /**
     * @return ClassInfoService
     */
    public function classInfoService()
    {
        return  $this->get('app.class_info_service');
    }
}
