<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 22.17
 */

namespace AppBundle\Service;

use AppBundle\Entity\Activity;
use AppBundle\Entity\StudentInfo;
use Doctrine\ORM\EntityManager;

class ResultService
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getLastResultsByClass($class) {
        $query = $this->em->createQueryBuilder()
            ->select('r, s')
            ->from('AppBundle:Result', 'r')
            ->innerJoin("r.studentInfo", "s")
            ->where("s.classInfo = :class")
            ->setParameter("class", $class)
            ->groupBy("r.activity")
            ->groupBy("r.studentInfo")
            ->getQuery();
        $results = $query->getResult();

        $lastResults = [];
        foreach ($results as $result) {
            $studentId = $result->getStudentInfo()->getId();
            $activityId = $result->getActivity()->getId();
            $lastResults[$studentId][$activityId] = $result->getValue();
        }

        return $lastResults;
    }

}