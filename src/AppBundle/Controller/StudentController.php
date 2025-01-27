<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\StudentInfo;
use AppBundle\Form\StudentType;
use AppBundle\Entity\User;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class StudentController extends Controller
{
    /**
     * @Route("/student/view/{studentInfo}", name="student_view")
     */
    public function profileAction(StudentInfo $studentInfo = null) {
        $session = $this->get('session');
        if ($this->isGranted("ROLE_STUDENT")) {
            $studentInfo = $this->getCurrentUser()->getMainStudentInfo();
            if ($studentInfo === null) {
                $userManager = $this->get('fos_user.user_manager');
                $user = $this->getCurrentUser();
                $user->setMainStudentInfo($this->get('app.student_info')->createStudentFromUser($user));
                $userManager->updateUser($user);
                $studentInfo = $this->getCurrentUser()->getMainStudentInfo();
                $session->set('showModal', true);
            }
        } else {
            if (!$studentInfo) {
                throw new NotFoundHttpException("Mokinys nerastas.");
            }
            if (!$studentInfo->getClassInfo()->getUser()->contains($this->getCurrentUser())) {
                throw new AccessDeniedException("Mokinys nepasiekiamas.");
            }
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
                "allResults" => $allResults,
                "showModal" => $session->get('showModal')
            ]
        );
    }

    /**
     * @Route("/student/edit/{studentInfo}", name="student_edit")
     */
    public function editAction(Request $request, StudentInfo $studentInfo = null) {
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
    public function deleteAction(Request $request, StudentInfo $studentInfo) {
        if (!$studentInfo) {
            throw new NotFoundHttpException("Mokinys nerastas.");
        }
        if (!$studentInfo->getClassInfo()->getUser()->contains($this->getCurrentUser())) {
            throw new AccessDeniedException("Mokinys nepasiekiamas.");
        }
        try {
            $this->getDoctrine()->getManager()->remove($studentInfo);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Mokinys pašalintas');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', 'Yra rezultatų, priklausančių šiam mokiniui.');
        }

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @Route("/student/create/{classInfo}", name="student_create")
     */
    public function createAction(Request $request, ClassInfo $classInfo = null) {
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
     * @Route("/students/create", name="students_create")
     * @Method("POST")
     */
    public function createMultipleAction(Request $request) {
        //todo padaryt normaliai
        $studentsNames = $request->get('studentsNames');
        $classInfo = $this->getDoctrine()->getRepository('AppBundle:ClassInfo')
            ->findClassesByTeacherAndClassId($this->getCurrentUser(), $request->get('classInfo'));

        $re = '/(?<=[0-9].)[[:upper:]].+?(?=\t\t)/u';
        preg_match_all($re, $studentsNames, $studentsNames, PREG_SET_ORDER, 0);

        $em = $this->getDoctrine()->getManager();
        foreach ($studentsNames as $name) {
            $studentInfo = new StudentInfo();

            $studentInfo->setClassInfo($classInfo);
            $studentInfo->setName($name[0]);
            $em->persist($studentInfo);
        }

        $em->flush();
        $this->addFlash('success', 'Mokiniai pridėti.');
        return new JsonResponse(['success' => 'true']);
    }

    /**
     * @Route("/student/join", name="student_join")
     */
    public function joinAction(Request $request) {
        $form = $this->createFormBuilder(null, array('action' => $this->generateUrl("student_join")))
            ->add('joinCode', TextType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($this->get('app.student_info')->joinStudentWithUser($form['joinCode']->getData())) {
                    $session = $this->get('session');
                    $session->set('showModal', false);
                    $this->addFlash('success', 'Prisijungta sėkmingai.');
                } else {
                    $this->addFlash('danger', 'Neteisingas kodas arba mokinys jau yra prijungtas.');
                }
            } else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return new RedirectResponse($request->headers->get('referer'));
        }
        return $this->render(
            'AppBundle:Student:join.html.twig',
            array(
                "form" => $form->createView(),
            )
        );
    }

    /**
     * @return User
     */
    private function getCurrentUser() {
        return $this->get('app.current_user_data_service')->getUser();
    }
}
