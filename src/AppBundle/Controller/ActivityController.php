<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\OriginType;
use AppBundle\Entity\Activity;
use AppBundle\Entity\User;
use AppBundle\Form\ActivityType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ActivityController extends Controller
{
    /**
     * @Route("/activities/list", name="activities_list")
     */
    public function listAction()
    {
        $activities = $this->get('app.activity')->getActivityList();
        return $this->render(
            'AppBundle:Activity:view.html.twig',
            array(
                "activities" => $activities
            )
        );
    }

    /**
     * @Route("/activities/edit/{activity}", name="activities_edit")
     */
    public function editAction(Request $request, Activity $activity = null)
    {
        if (!$activity) {
            throw new NotFoundHttpException("Rungtis nerasta.");
        }
        if ($activity->getUser() !== $this->getCurrentUser()) {
            throw new AccessDeniedException("Rungtis nepasiekiama.");
        }
        $form = $this->createForm(
            ActivityType::class,
            $activity,
            array(
                'action' => $this->generateUrl("activities_edit", array('activity' => $activity->getId()))
            )
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Rungtis atnaujinta.');
            } else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return $this->redirectToRoute("activities_list");
        }
        return $this->render(
            'AppBundle:Activity:edit.html.twig',
            array(
                "form" => $form->createView()
            )
        );
    }

    /**
     * @Route("/activities/delete/{activity}", name="activities_delete")
     */
    public function deleteAction(Activity $activity)
    {
        if (!$activity) {
            throw new NotFoundHttpException("Rungtis nerasta.");
        }
        if ($activity->getUser() !== $this->getCurrentUser()) {
            throw new AccessDeniedException("Rungtis nepasiekiama.");
        }
        try {
            $this->getDoctrine()->getManager()->remove($activity);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Rungtis pašalinta');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', 'Yra rezultatų, priklausančių šiai rungčiai.');
        }

        return $this->redirectToRoute("activities_list");
    }

    /**
     * @Route("/activities/create", name="activities_create")
     */
    public function createAction(Request $request)
    {
        $activity = new Activity($this->getCurrentUser());
        $form = $this->createForm(
            ActivityType::class,
            $activity,
            array(
                'action' => $this->generateUrl("activities_create")
            )
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($activity);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Rungtis pridėta.');
            } else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return $this->redirectToRoute("activities_list");
        }
        return $this->render(
            'AppBundle:Activity:create.html.twig',
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
