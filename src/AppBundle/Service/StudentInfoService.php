<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.13
 * Time: 15.22
 */

namespace AppBundle\Service;

use AppBundle\Entity\StudentInfo;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class StudentInfoService
{

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getStudentListByClass($classInfo)
    {
        $repository = $this->em->getRepository('AppBundle:StudentInfo');
        $query = $repository->createQueryBuilder('r')
            ->where("r.classInfo = :class")
            ->setParameter("class", $classInfo)
            ->orderBy('r.name')
            ->getQuery();
        $students = $query->getResult();

        return $students;
    }

    public function createStudentFromUser(User $user) {
        $studentInfo = new StudentInfo();
        $studentInfo->setName($user->getName());
        $this->em->persist($studentInfo);
        $this->em->flush();

        return $studentInfo;
    }
}
