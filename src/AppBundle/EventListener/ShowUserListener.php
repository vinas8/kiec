<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 11/25/16
 * Time: 11:14 AM
 */

namespace AppBundle\EventListener;

use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Model\UserModel;

class ShowUserListener
{
    protected $tokenStorage;
    private $user;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onShowUser(ShowUserEvent $event) {

        $this->user = new UserModel();
        $this->user->setAvatar($this->tokenStorage->getToken()->getUser()->getProfilePicture());
        $this->user->setIsOnline(true);
        $this->user->setMemberSince(new \DateTime('now'));
        $this->user->setUsername($this->tokenStorage->getToken()->getUser()->getEmail());
        $event->setUser($this->user);

    }

    protected function getUser() {
        return $this->tokenStorage->getToken()->getUser();
    }
}