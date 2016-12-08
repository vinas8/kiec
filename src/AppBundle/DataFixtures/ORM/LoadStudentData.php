<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\StudentInfo;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadStudentData extends AbstractFixture   implements OrderedFixtureInterface
{
    /**
     * @var User
     */
    private $user;

    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getDummyStudents() as $student) {
            $studentInfo = new StudentInfo();
            $studentInfo->setName($student['name']);
            $studentInfo->setBirthDate(new \DateTime());
            $studentInfo->setClassInfo($this->getReference('classInfo'));

            $manager->persist($studentInfo);
        }
        $manager->flush();
        $this->addReference('studentInfo', $studentInfo);
    }

    public function getOrder()
    {
        return 2;
    }

    private function getDummyStudents() {
        return array(

            ['name' => 'Antanas Antonaitis'],
            ['name' => 'Bronius Bronaitis'],
            ['name' => 'Jokubas Jokubaitis'],
            ['name' => 'Jonas Jonaitis'],
            ['name' => 'Paulius Paulaitis'],

        );
    }
}