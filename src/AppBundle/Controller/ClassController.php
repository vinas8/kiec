<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ClassController extends Controller
{
    /**
     * @Route("/class/view/{classInfo}", name="class_view")
     */
    public function classAction(ClassInfo $classInfo = null)
    {
        if ($classInfo) {
            if (!$classInfo->getUser()->contains($this->get('app.current_user_data_service')->getUser())) {
                throw new AccessDeniedException("Klasė nepasiekiama.");
            }
        }
        $classes = $this->getDoctrine()->getRepository('AppBundle:ClassInfo')
            ->findClassesByTeacher($this->get('app.current_user_data_service')->getUser());
        return $this->render(
            '@App/Class/view.html.twig',
            array(
                'class' => $classInfo,
                'classes' => $classes
            )
        );
    }
}
