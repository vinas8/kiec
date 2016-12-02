<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 22.17
 */

namespace AppBundle\Service;

use AppBundle\Entity\Activity;
use AppBundle\Entity\Result;
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

    public function getLastResultsByClass($classInfo)
    {
        $query = $this->em->createQueryBuilder()
            ->select('ro')
            ->from(Result::class, 'ro')
            ->leftJoin(
                Result::class,
                'rt',
                'WITH',
                'ro.activity = rt.activity AND ro.studentInfo = rt.studentInfo AND ro.timestamp < rt.timestamp'
            )
            ->innerJoin('ro.studentInfo', 's', 'WITH', 's.classInfo = :class')
            ->setParameter("class", $classInfo)
            ->where('rt.timestamp is NULL')
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

    public function getBestResultsByStudent($studentInfo)
    {
        $query = $this->em->createQueryBuilder()
            ->select('r AS result')
            ->addSelect('MAX(r.value) AS max_value')
            ->from(Result::class, 'r')
            ->innerJoin("r.activity", "s")
            ->where("r.studentInfo = :student")
            ->setParameter("student", $studentInfo)
            ->groupBy("r.activity")
            ->orderBy("s.name")
            ->getQuery();
        $results = $query->getResult();

        return $results;
    }

    public function getResultListByStudent($studentInfo)
    {
        $repository = $this->em->getRepository('AppBundle:Result');
        $query = $repository->createQueryBuilder('r')
            ->where("r.studentInfo = :student")
            ->setParameter("student", $studentInfo)
            ->orderBy('r.timestamp', 'DESC')
            ->getQuery();
        $results = $query->getResult();

        $allResults = [];
        foreach ($results as $result) {
            $activityId = $result->getActivity()->getId();
            if (!isset($allResults[$activityId])) {
                $allResults[$activityId] = [];
            }
            array_push($allResults[$activityId], $result);
        }

        return $allResults;
    }
}
