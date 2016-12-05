<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 20.02
 */

namespace AppBundle\Service;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Activity;

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

    public function getActivityList()
    {
        $repository = $this->em->getRepository('AppBundle:Activity');
        $query = $repository->createQueryBuilder('r')
            ->orderBy('r.name')
            ->getQuery();
        $activities = $query->getResult();

        return $activities;
    }

    public function createActivity($activity)
    {
        $this->em->persist($activity);
        $this->em->flush();
    }

    public function deleteActivity($activity)
    {
        try {
            $this->em->remove($activity);
            $this->em->flush();
            return true;
        } catch (ForeignKeyConstraintViolationException $e) {
            return false;
        }
    }

    public function editActivity($activity)
    {
        $this->em->persist($activity);
        $this->em->flush();
    }
}
