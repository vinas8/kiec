<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Activity;
use AppBundle\Entity\Result;
use AppBundle\Entity\StudentInfo;
use AppBundle\Entity\User;
use AppBundle\Form\ResultType;
use AppBundle\Form\TopResultType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ResultController extends Controller
{
    /**
     * @Route("/result/edit/{result}", name="result_edit")
     */
    public function editAction(Request $request, Result $result = null)
    {
        if (!$result) {
            throw new NotFoundHttpException("Rezultatas nerastas.");
        }
        if ($result->getUser() !== $this->getUser()) {
            throw new AccessDeniedException("Rezultatas nepasiekiamas.");
        }
        $form = $this->createForm(
            ResultType::class,
            $result,
            array(
                'action' => $this->generateUrl("result_edit", array('result' => $result->getId()))
            )
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Rezultatas atnaujintas.');
                return new RedirectResponse($request->headers->get('referer'));
            } else {
                $this->addFlash('danger', 'Neteisingai įvesti duomenys.');
                return new RedirectResponse($request->headers->get('referer'));
            }
        }
        return $this->render(
            'AppBundle:Result:edit.html.twig',
            array(
                "form" => $form->createView(),
                "result" => $result
            )
        );
    }

    /**
     * @Route("/result/delete/{result}", name="result_delete")
     */
    public function deleteAction(Result $result, Request $request)
    {
        if (!$result) {
            throw new NotFoundHttpException("Rezultatas nerastas.");
        }
        if ($result->getUser() !== $this->getUser()) {
            throw new AccessDeniedException("Rezultatas nepasiekiamas.");
        }
        $this->getDoctrine()->getManager()->remove($result);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Rezultatas pašalintas');

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @Route("/result/create/{studentInfo}/{activity}", name="result_create")
     */
    public function createAction(Request $request, StudentInfo $studentInfo = null, Activity $activity = null)
    {
        if ($this->isGranted("ROLE_STUDENT")) {
            $studentInfo = $this->getCurrentUser()->getMainStudentInfo();
//            dump($studentInfo);
            dump("MIAAAAAAAAAAAAAAAAAAAU");
        }
        else if ($studentInfo) {
            if (!$studentInfo->getClassInfo()->getUser()->contains($this->getCurrentUser())) {
                throw new AccessDeniedException("Mokinys nepasiekiamas.");
            }
        }
        $result = new Result($activity, $studentInfo, $this->getCurrentUser());
        $form = $this->createForm(ResultType::class, $result, array('action' => $this->generateUrl("result_create")));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($result);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Rezultatas pridėtas.');
                return new RedirectResponse($request->headers->get('referer'));
            } else {
                $this->addFlash('danger', 'Neteisingai įvesti duomenys.');
                return new RedirectResponse($request->headers->get('referer'));
            }
        }
        return $this->render(
            'AppBundle:Result:create.html.twig',
            array(
                "form" => $form->createView(),
            )
        );
    }

    /**
     * @Route("/result/top", name="result_top")
     */
    public function resultTopAction(Request $request)
    {
        $form = $this->createForm(TopResultType::class);
        $form->handleRequest($request);
        $results = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $results = $this->get('app.result')->getTopResults($form);
            } else {
                $this->addFlash('danger', 'Neteisingai įvesti duomenys.');
            }
        }
        return $this->render(
            'AppBundle:Result:top.html.twig',
            array(
                "form" => $form->createView(),
                "results" => $results
            )
        );
    }

    /**
     * @return User
     */
    private function getCurrentUser()
    {
        return $this->get('app.current_user_data_service')->getUser();
    }
}
