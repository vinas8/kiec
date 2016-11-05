<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 22.17
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class ResultService
{
    /**
     * @var EntityManager
     */

    private $em;

    private $studentInfoService;

    private $activityService;

    public function __construct(EntityManager $em, StudentInfoService $studentInfoService, ActivityService $activityService)
    {
        $this->em = $em;
        $this->studentInfoService = $studentInfoService;
        $this->activityService = $activityService;
    }

    public function getLastResultsByClass($classId) {
        $activities = $this->activityService->getActivityList();
        $students = $this->studentInfoService->getStudentListByClass($classId);
        $results = array();
        foreach ($activities as $activity) {
            foreach ($students as $student) {
                $result = $this->getLastResult($activity, $student);
                if ($result !== null) {
                    array_push($results, $result);
                }
            }
        }

        return $results;
    }

    public function getLastResult($activity, $student) {
        $repository = $this->em->getRepository('AppBundle:Result');
        $query = $repository->createQueryBuilder('r')
            ->where("r.studentInfo = :student")
            ->setParameter("student", $student)
            ->andWhere("r.activity = :activity")
            ->setParameter("activity", $activity)
            ->orderBy('r.timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery();
        $result = $query->getOneOrNullResult();

        return $result;
    }

    public function getBestResultsByStudent($studentId) {
        $activities = $this->activityService->getActivityList();
        $student = $this->studentInfoService->getStudentById($studentId);
        $results = array();
        foreach ($activities as $activity) {
            $result = $this->getBestResult($activity, $student);
            if ($result !== null) {
                array_push($results, $result);
            }
        }

        return $results;
    }

    public function getBestResult($activity, $student) {
        $repository = $this->em->getRepository('AppBundle:Result');
        $query = $repository->createQueryBuilder('r')
            ->where("r.studentInfo = :student")
            ->setParameter("student", $student)
            ->andWhere("r.activity = :activity")
            ->setParameter("activity", $activity)
            ->orderBy('r.value', 'DESC')
            ->setMaxResults(1)
            ->getQuery();
        $result = $query->getOneOrNullResult();

        return $result;
    }

    public function getResultListByStudent($studentId) {
        $repository = $this->em->getRepository('AppBundle:Result');
        $student = $this->studentInfoService->getStudentById($studentId);
        $query = $repository->createQueryBuilder('r')
            ->where("r.studentInfo = :student")
            ->setParameter("student", $student)
            ->orderBy('r.timestamp', 'DESC')
            ->getQuery();
        $results = $query->getResult();

        return $results;
    }

}