<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use AppBundle\Form\ClassInfoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClassController extends Controller
{
    /**
     * @Route("/class/view/{classInfo}", name="class_view")
     */
    public function viewAction(ClassInfo $classInfo = null)
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

    /**
     * @Route("/class/edit/{classInfo}", name="class_edit")
     */
    public function editAction(Request $request, ClassInfo $classInfo = null)
    {
        if (!$classInfo) {
            throw new NotFoundHttpException("Klasė nerasta.");
        }
        if (!$classInfo->getUser()->contains($this->get('app.current_user_data_service')->getUser())) {
            throw new AccessDeniedException("Klasė nepasiekiama.");
        }
        $form = $this->createForm(
            ClassInfoType::class,
            $classInfo,
            array(
                'action' => $this->generateUrl("class_edit", array('classInfo' => $classInfo->getId()))
            )
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Klasė atnaujinta.');
            } else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return new RedirectResponse($request->headers->get('referer'));
        }
        return $this->render(
            'AppBundle:Class:edit.html.twig',
            array(
                "form" => $form->createView()
            )
        );
    }

    /**
     * @Route("/class/delete/{classInfo}", name="class_delete")
     */
    public function deleteAction(Request $request, ClassInfo $classInfo)
    {
        if (!$classInfo) {
            throw new NotFoundHttpException("Klasė nerasta.");
        }
        if (!$classInfo->getUser()->contains($this->get('app.current_user_data_service')->getUser())) {
            throw new AccessDeniedException("Klasė nepasiekiama.");
        }
        try {
            $this->getDoctrine()->getManager()->remove($classInfo);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Rungtis pašalinta');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', 'Yra mokinių, priklausančių šiai klasei.');
        }

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @Route("/class/create", name="class_create")
     */
    public function createAction(Request $request)
    {
        $classInfo = new ClassInfo(array($this->get('app.current_user_data_service')->getUser()));
        $form = $this->createForm(
            ClassInfoType::class,
            $classInfo,
            array(
                'action' => $this->generateUrl("class_create")
            )
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($classInfo);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Klasė sukurta.');
            } else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return new RedirectResponse($request->headers->get('referer'));
        }
        return $this->render(
            'AppBundle:Class:create.html.twig',
            array(
                "form" => $form->createView(),
            )
        );
    }
}
