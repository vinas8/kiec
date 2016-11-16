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

    public function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('user_profile', array($this, 'user_profile'))
        );
    }

    public function user_profile()
    {
        return $this->getUser();
    }

    public function getName()
    {
        return 'TwigExtensions';
    }
}