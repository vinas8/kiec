<?php

/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 18.49
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Date;


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

    public function getStudentListByClass($classId) {
        $repository = $this->em->getRepository('AppBundle:ClassInfo');
        $class = $repository->findOneById($classId);
        $repository = $this->em->getRepository('AppBundle:StudentInfo');
        $students = $repository->findByClassInfo($class);

        return $students;
    }

    public function getStudentById($studentId) {
        $repository = $this->em->getRepository('AppBundle:StudentInfo');
        $student = $repository->findOneById($studentId);

        return $student;
    }

}