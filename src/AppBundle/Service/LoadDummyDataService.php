<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.12.8
 * Time: 22.20
 */

namespace AppBundle\Service;


use AppBundle\DBAL\Types\OriginType;
use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\Result;
use AppBundle\Entity\StudentInfo;
use Doctrine\ORM\EntityManager;

class LoadDummyDataService
{
    private $em;

    /**
     * LoadDummyDataService constructor.
     * @param $em
     */

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function loadDummyData($user) {
        foreach ($this->getDummyClasses() as $class)
        {
            $dummyClass = new ClassInfo();
            $dummyClass->setName($class['name']);
            $dummyClass->setUser(array($user));
            $this->em->persist($dummyClass);
            $classSet[] = $dummyClass;
        }
        $this->em->flush();
        foreach ($classSet as $class) {
            foreach ($this->getDummyStudents() as $student) {
                $dummyClass = new StudentInfo();
                $dummyClass->setName($student['name']);
                $dummyClass->setBirthDate(new \DateTime());
                $dummyClass->setClassInfo($class);
                $this->em->persist($dummyClass);
                $studentSet[] = $dummyClass;
            }
        }
        $this->em->flush();

        $repository = $this->em->getRepository('AppBundle:Activity');
        $query = $repository->createQueryBuilder('r')
            ->where('r.origin = :origin')
            ->setParameter('origin', OriginType::NATIVE)
            ->getQuery();
        $activities = $query->getResult();

        foreach ($activities as $activity) {
            foreach ($studentSet as $student) {
                for ($x = 0; $x <= rand(3, 8); $x++) {
                    $dummyClass = new Result($activity, $student, $user);
                    $dummyClass->setTimestamp(new \DateTime());
                    $dummyClass->setValue(rand(5, 15));
                    $this->em->persist($dummyClass);
                }
            }
        }

        $this->em->flush();
    }

    private function getDummyClasses() {
        return array (
            ['name' => '7a'],
            ['name' => '8b'],
            ['name' => '9c']
        );
    }
    private function getDummyStudents() {
        return array (
            ['name' => 'Antanas Antonaitis'],
            ['name' => 'Bronius Bronaitis'],
            ['name' => 'Jonas Jonaitis'],
            ['name' => 'Petras Petraitis'],
            ['name' => 'Benas Benaitis'],
        );
    }
}