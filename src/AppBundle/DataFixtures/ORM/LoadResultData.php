<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\Result;
use AppBundle\Entity\StudentInfo;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadResultData extends AbstractFixture   implements OrderedFixtureInterface
{
    /**
     * @var User
     */
    private $user;

    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    private $activities;

    public function setActivities($activities)
    {
        $this->activities = $activities;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->activities as $activity) {
            $result = new Result($this->user);
            $result->setTimestamp(new \DateTime());
            $result->setActivity($activity);
            $result->setStudentInfo($this->getReference('studentInfo'));
            $result->setValue(rand(1, 100));

            $manager->persist($result);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

    private function getDummyResults() {
        return array(

            ['value' => 8],
            ['value' => 8],
            ['value' => 8],
            ['value' => 8],
            ['value' => 8],
            ['value' => 8],
            ['value' => 8],

        );
    }
}