<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 20.02
 */

namespace AppBundle\Service;

use AppBundle\DBAL\Types\OriginType;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class ActivityService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var User
     */
    private $currentUser;

    public function __construct(EntityManager $em, CurrentUserDataService $currentUserDataService)
    {
        $this->em = $em;
        $this->currentUser = $currentUserDataService->getUser();
    }

    public function getActivityList()
    {
        $repository = $this->em->getRepository('AppBundle:Activity');
        $query = $repository->createQueryBuilder('r')
            ->where('r.user = :user')
            ->orWhere('r.origin = :origin')
            ->setParameter('user', $this->currentUser)
            ->setParameter('origin', OriginType::NATIVE)
            ->orderBy('r.name')
            ->getQuery();
        $activities = $query->getResult();


        return $activities;
    }
}
