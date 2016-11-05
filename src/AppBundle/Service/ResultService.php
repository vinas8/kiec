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
}