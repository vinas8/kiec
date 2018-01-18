<?php

namespace AppBundle\Controller;

use AppBundle\Exception\LessonException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted('ROLE_TEACHER')) {
            try {
                $response = $this->forward('AppBundle:Lesson:current');
            } catch (LessonException $e) {
                $this->addFlash('info', $e->getMessage());
                $response = $this->forward('AppBundle:Class:view');
            }
        } else if ($this->isGranted('ROLE_STUDENT')) {
            $response = $this->forward('AppBundle:Student:profile');
        } else if ($this->isGranted('ROLE_NOT_ASSIGNED')) {

            $form = $this->createFormBuilder(null, array('action' => $this->generateUrl("homepage")))
                ->add('role', ChoiceType::class, array(
                    'choices' => array(
                        'Mokinys' => "ROLE_STUDENT",
                        'Mokytojas' => "ROLE_TEACHER",
                    )))
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    if ($this->get('app.current_user_data_service')->assignRole($form['role']->getData())) {
                        $this->addFlash('success', 'Rolė pakeista sėkmingai.');
                    } else {
                        $this->addFlash('danger', 'Neteisingai parinkta rolė');
                    }
                } else {
                    $this->addFlash('danger', 'Nepavyko pakeisti rolės, bandykite dar kartą');
                }
                return new RedirectResponse($request->headers->get('referer'));
            }

            $response = $this->render(
                'AppBundle:Home:choose_role.html.twig',
                array(
                    "form" => $form->createView(),
                )
            );
        } else {
            $response = $this->render(
                'AppBundle:Home:index.html.twig'
            );
        }

        return $response;
    }

}
