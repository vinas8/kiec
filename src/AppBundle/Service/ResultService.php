<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 22.17
 */

namespace AppBundle\Service;

use AppBundle\DBAL\Types\BestResultDeterminationType;
use AppBundle\Entity\Activity;
use AppBundle\Entity\Result;
use AppBundle\Entity\StudentInfo;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class ResultService
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
        if ($studentInfo->getUser() === null) {
            $query = $this->em->createQueryBuilder()
                ->select('r AS result')
                ->addSelect('MAX(r.value) AS max_value, MIN(r.value) AS min_value')
                ->from(Result::class, 'r')
                ->innerJoin("r.activity", "s")
                ->where("r.studentInfo = :student")
                ->setParameter("student", $studentInfo)
                ->groupBy("r.activity")
                ->orderBy("s.name")
                ->getQuery();
            $results = $query->getResult();
        }
        else {
            $query = $this->em->createQueryBuilder()
                ->select('r AS result')
                ->addSelect('MAX(r.value) AS max_value, MIN(r.value) AS min_value')
                ->from(Result::class, 'r')
                ->innerJoin("r.activity", "a")
                ->innerJoin("r.studentInfo", "s")
                ->where("s.user = :user")
                ->setParameter("user", $studentInfo->getUser())
                ->groupBy("r.activity")
                ->orderBy("a.name")
                ->getQuery();
            $results = $query->getResult();
        }

        return $results;
    }

    public function getResultListByStudent($studentInfo)
    {
        if ($studentInfo->getUser() === null) {
            $repository = $this->em->getRepository('AppBundle:Result');
            $query = $repository->createQueryBuilder('r')
                ->where("r.studentInfo = :student")
                ->setParameter("student", $studentInfo)
                ->orderBy('r.timestamp', 'DESC')
                ->getQuery();
            $results = $query->getResult();
        }
        else {
            $repository = $this->em->getRepository('AppBundle:Result');
            $query = $repository->createQueryBuilder('r')
                ->innerJoin("r.studentInfo", "s")
                ->where("s.user = :user")
                ->setParameter("user", $studentInfo->getUser())
                ->orderBy('r.timestamp', 'DESC')
                ->getQuery();
            $results = $query->getResult();
        }

        $allResults = [];
        foreach ($results as $result) {
            $activityId = $result->getActivity()->getId();
            $allResults[$activityId][] = $result;
        }

        return $allResults;
    }

    public function addNewResults($results)
    {
        foreach ($results->getActivities() as $activityResults) {
            foreach ($activityResults->getResults() as $result) {
                if ($result->getValue() !== null) {
                    $this->em->persist($result);
                }
            }
        }
        $this->em->flush();
    }

    public function getTopResults($form)
    {
        $query = $this->em->createQueryBuilder()
            ->select('r AS result')
            ->from(Result::class, 'r')
            ->join("r.studentInfo", "s", "WITH", "s.classInfo IN (:classInfo)")
            ->where("r.activity = :activity")
            ->setParameter("activity", $form->getData()->getActivity())
            ->setParameter("classInfo", $form->getData()->getClassInfo())
            ->setMaxResults($form->getData()->getMaxResults())
            ->groupBy("r.studentInfo");
             if ($form->getData()->getActivity()->getBestResultDetermination() === BestResultDeterminationType::MAX) {
                 $query->addSelect('MAX(r.value) AS top_value');
                 $query->orderBy('top_value', 'DESC');
             } else {
                 $query->addSelect('MIN(r.value) AS top_value');
                 $query->orderBy('top_value');
             }
        return $query->getQuery()->getResult();
    }
}
