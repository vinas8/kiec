<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Result;
use AppBundle\Entity\StudentInfo;
use AppBundle\Form\ResultType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class ResultController extends Controller
{
    /**
     * @Route("/result/edit/{result}", name="result_edit")
     */
    public function editAction(Result $result, Request $request)
    {
        $form = $this->createForm(ResultType::class, $result, array('action' => $this->generateUrl("result_edit", array('result' => $result->getId()))));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Rezultatas atnaujintas.');
            }
            else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return new RedirectResponse($request->headers->get('referer'));
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
        $this->getDoctrine()->getManager()->remove($result);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Rezultatas pašalintas');

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @Route("/result/create/{studentInfo}", name="result_create")
     */
    public function createAction(StudentInfo $studentInfo = null, Request $request)
    {
        $result = new Result(null, $studentInfo);
        $form = $this->createForm(ResultType::class, $result, array('action' => $this->generateUrl("result_create")));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($result);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Rezultatas pridėtas.');
            }
            else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }

            return new RedirectResponse($request->headers->get('referer'));
        }
        return $this->render(
            'AppBundle:Result:create.html.twig',
            array(
                "form" => $form->createView(),
            )
        );
    }
}
