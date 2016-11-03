<?php

/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 18.49
 */

namespace AppBundle\Service;

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

    public function getFullDataByClass($classId) {
        $repository = $this->em->getRepository('AppBundle:StudentInfo');
        $students = $repository->findByClassInfo($classId);

        return $students;
    }

}