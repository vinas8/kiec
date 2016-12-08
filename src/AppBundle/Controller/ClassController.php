<?php

namespace AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadClassData;
use AppBundle\DataFixtures\ORM\LoadResultData;
use AppBundle\DataFixtures\ORM\LoadStudentData;
use AppBundle\Entity\ClassInfo;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;

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
        // load test data
        if (empty($classes)) {
            $loader = new Loader();
            $fixture = new LoadClassData();
            $fixture->setUser($this->get('app.current_user_data_service')->getUser());
            $loader->addFixture($fixture);
            $fixture = new LoadStudentData();
            $fixture->setUser($this->get('app.current_user_data_service')->getUser());
            $loader->addFixture($fixture);
            $fixture = new LoadResultData();
            $fixture->setUser($this->get('app.current_user_data_service')->getUser());
            $fixture->setActivities($this->get('app.activity')->getActivityList());
            $loader->addFixture($fixture);
            $purger = new ORMPurger($this->getDoctrine()->getManager());
            $executor = new ORMExecutor($this->getDoctrine()->getManager(), $purger);
            $executor->execute($loader->getFixtures(), true);

        }
        return $this->render(
            '@App/Class/view.html.twig',
            array(
                'class' => $classInfo,
                'classes' => $classes
            )
        );
    }
}
