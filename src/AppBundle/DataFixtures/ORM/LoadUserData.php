<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setEmail('admin');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(array('ROLE_TEACHER'));

        $manager->persist($userAdmin);
        $manager->flush();
    }
}