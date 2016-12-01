<?php
namespace AppBundle\Twig;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Created by PhpStorm.
 * User: zn
 * Date: 11/16/16
 * Time: 3:36 PM
 */
class TwigExtensions extends \Twig_Extension
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('profile_picture', array($this, 'getProfilePicture')),
            new \Twig_SimpleFunction('profile_email', array($this, 'getProfileEmail')),
            new \Twig_SimpleFunction('profile_name', array($this, 'getProfileName')),
            new \Twig_SimpleFunction('profile_id', array($this, 'getProfileId'))

        );
    }

    private function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    public function getProfileEmail()
    {
        return $this->getUser()->getEmail();
    }

    public function getProfilePicture()
    {
        return $this->getUser()->getProfilePicture();
    }

    public function getProfileName()
    {
        return $this->getUser()->getName();
    }

    public function getProfileId()
    {
        return $this->getUser()->getId();
    }

    public function getName()
    {
        return 'TwigExtensions';
    }
}
