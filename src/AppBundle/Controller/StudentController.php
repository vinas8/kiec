<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\StudentInfo;
use AppBundle\Form\StudentType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class StudentController extends Controller
{
    /**
     * @Route("/student/view/{studentInfo}", name="student_view")
     */
    public function profileAction(StudentInfo $studentInfo = null)
    {
        if (!$studentInfo) {
            throw new NotFoundHttpException("Mokinys nerastas.");
        }
        if (!$studentInfo->getClassInfo()->getUser()->contains($this->getCurrentUser())) {
            throw new AccessDeniedException("Mokinys nepasiekiamas.");
        }

        $activityService = $this->get('app.activity');
        $resultService = $this->get('app.result');

        $activities = $activityService->getActivityList();
        $bestResults = $resultService->getBestResultsByStudent($studentInfo);
        $allResults = $resultService->getResultListByStudent($studentInfo);

        return $this->render(
            'AppBundle:Student:view.html.twig',
            [
                "student" => $studentInfo,
                "activities" => $activities,
                "bestResults" => $bestResults,
                "allResults" => $allResults
            ]
        );
    }

    /**
     * @Route("/student/edit/{studentInfo}", name="student_edit")
     */
    public function editAction(Request $request, StudentInfo $studentInfo = null)
    {
        if (!$studentInfo) {
            throw new NotFoundHttpException("Mokinys nerastas.");
        }
        if (!$studentInfo->getClassInfo()->getUser()->contains($this->getCurrentUser())) {
            throw new AccessDeniedException("Mokinys nepasiekiamas.");
        }
        $form = $this->createForm(
            StudentType::class,
            $studentInfo,
            array(
                'action' => $this->generateUrl("student_edit", array('studentInfo' => $studentInfo->getId()))
            )
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Mokinys atnaujintas.');
            } else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return new RedirectResponse($request->headers->get('referer'));
        }
        return $this->render(
            'AppBundle:Student:edit.html.twig',
            array(
                "form" => $form->createView()
            )
        );
    }

    /**
     * @Route("/student/delete/{studentInfo}", name="student_delete")
     */
    public function deleteAction(Request $request, StudentInfo $studentInfo)
    {
        if (!$studentInfo) {
            throw new NotFoundHttpException("Mokinys nerastas.");
        }
        if (!$studentInfo->getClassInfo()->getUser()->contains($this->getCurrentUser())) {
            throw new AccessDeniedException("Mokinys nepasiekiamas.");
        }
        try {
            $this->getDoctrine()->getManager()->remove($studentInfo);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Rungtis pašalinta');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', 'Yra rezultatų, priklausančių šiam mokiniui.');
        }

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @Route("/student/create/{classInfo}", name="student_create")
     */
    public function createAction(Request $request, ClassInfo $classInfo = null)
    {
        $studentInfo = new StudentInfo();
        $studentInfo->setClassInfo($classInfo);
        $form = $this->createForm(
            StudentType::class,
            $studentInfo,
            array(
                'action' => $this->generateUrl("student_create")
            )
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($studentInfo);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Mokinys pridėtas.');
            } else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return new RedirectResponse($request->headers->get('referer'));
        }
        return $this->render(
            'AppBundle:Student:create.html.twig',
            array(
                "form" => $form->createView(),
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
