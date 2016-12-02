<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 12/2/16
 * Time: 10:31 AM
 */

namespace AppBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CurrentUserDataService
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}

