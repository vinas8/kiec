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

    public function getActivityList() {
        $repository = $this->em->getRepository('AppBundle:Activity');
        $query = $repository->createQueryBuilder('r')
            ->orderBy('r.name')
            ->getQuery();
        $activities = $query->getResult();

        return $activities;
    }

}