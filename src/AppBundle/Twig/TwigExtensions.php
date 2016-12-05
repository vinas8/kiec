<?php
namespace AppBundle\Twig;

use AppBundle\GlobalConstants;
use AppBundle\Service\CurrentUserDataService;
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
    protected $currentUserDataService;

    public function __construct(CurrentUserDataService $currentUserDataService)
    {
        $this->currentUserDataService = $currentUserDataService;
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
        return $this->currentUserDataService->getUser();
    }

    public function getProfileEmail()
    {
        return $this->getUser()->getEmail();
    }

    public function getProfilePicture()
    {
        $profilePic = $this->getUser()->getProfilePicture();

        if (!file_exists($profilePic)) {
            return GlobalConstants::PROFILE_IMAGE_DEFAULT;
        }

        return $profilePic;
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
