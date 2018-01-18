<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 12/2/16
 * Time: 10:31 AM
 */

namespace AppBundle\Service;

use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CurrentUserDataService
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var UserManager
     */
    protected $userManager;

    public function __construct(TokenStorage $tokenStorage, UserManager $userManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userManager = $userManager;
    }

    public function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    public function assignRole($role) {
        //Todo: validacija
//        $studentInfo = $this->em->getRepository('AppBundle:StudentInfo')->findOneByJoinCode($joinCode);
////        dump($studentInfo);
//        if ($studentInfo === null) {
//            return false;
//        }
//        if ($studentInfo->getUser() !== null) {
//            return false;
//        }
//        else {
            $user = $this->getUser();
            $user->setRoles(array($role));
            $this->userManager->updateUser($user);
        return true;
//        }
    }
}
