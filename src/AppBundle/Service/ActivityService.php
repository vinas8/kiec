<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 20.02
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class ActivityService
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getAllActivities() {
        $repository = $this->em->getRepository('AppBundle:Activity');
        $activities = $repository->findAll();

        return $activities;
    }

}