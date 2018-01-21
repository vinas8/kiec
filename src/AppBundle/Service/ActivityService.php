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
use Doctrine\Common\Collections\ArrayCollection;
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

    public function __construct(EntityManager $em, CurrentUserDataService $currentUserDataService) {
        $this->em = $em;
        $this->currentUser = $currentUserDataService->getUser();
    }

    public function getActivityList() {
        $repository = $this->em->getRepository('AppBundle:Activity');
        $users = new ArrayCollection();
        $users->add($this->currentUser);
        foreach ($this->currentUser->getStudents() as $student) {
            foreach ($student->getClassInfo()->getUser() as $teacher) {
                $users->add($teacher);
            }
        }

        $query = $repository->createQueryBuilder('r')
            ->where('r.user IN (:users)')
            ->orWhere('r.origin = :origin')
            ->setParameter('users', $users)
            ->setParameter('origin', OriginType::NATIVE)
            ->orderBy('r.name')
            ->getQuery();
        $activities = $query->getResult();


        return $activities;
    }
}
