<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadClassData extends AbstractFixture  implements OrderedFixtureInterface
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
        foreach ($this->getDummyClasses() as $class) {
            $classInfo = new ClassInfo();
            $classInfo->setName($class['name']);
            $classInfo->setUser(array($this->user));

            $manager->persist($classInfo);
        }
        $manager->flush();
        $this->addReference('classInfo', $classInfo);
    }

    public function getOrder()
    {
        return 1;
    }

    private function getDummyClasses() {
        return array(

                ['name' => '7a'],
                ['name' => '8b'],
                ['name' => '9c']

        );
    }
}