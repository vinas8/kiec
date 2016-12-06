<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Activity;
use AppBundle\Form\ActivityType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ActivityController extends Controller
{
    /**
     * @Route("/activities/view", name="activities_view")
     */
    public function viewAction()
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
    public function editAction(Activity $activity, Request $request)
    {
        $form = $this->createForm(ActivityType::class, $activity, array('action' => $this->generateUrl("activities_edit", array('activity' => $activity->getId()))));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Rungtis atnaujinta.');
            }
            else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return $this->redirectToRoute("activities_view");
        }
        return $this->render(
            'AppBundle:Activity:edit.html.twig',
            array(
                "form" => $form->createView(),
                "activity" => $activity
            )
        );
    }

    /**
     * @Route("/activities/delete/{activity}", name="activities_delete")
     */
    public function deleteAction(Activity $activity)
    {
        try {
            $this->getDoctrine()->getManager()->remove($activity);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Rungtis pašalinta');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', 'Yra rezultatų, priklausančių šiai rungčiai.');
        }

        return $this->redirectToRoute("activities_view");
    }

    /**
     * @Route("/activities/create", name="activities_create")
     */
    public function createAction(Request $request)
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityType::class, $activity, array('action' => $this->generateUrl("activities_create")));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($activity);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Rungtis pridėta.');
            }
            else {
                $this->addFlash('danger', 'Netinkama reikšmė.');
            }
            return $this->redirectToRoute("activities_view");
        }
        return $this->render(
            'AppBundle:Activity:create.html.twig',
            array(
                "form" => $form->createView(),
            )
        );
    }
}
