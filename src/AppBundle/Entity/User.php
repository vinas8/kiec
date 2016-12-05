<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends FOSUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
    private $google_id;

    /** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
    private $google_access_token;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="Vardas yra per trumpas",
     *     maxMessage="Vardas yra per ilgas",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", length=255, nullable=true)
     */
    private $profilePicture;

    /**
     * @ORM\Column(name="profile_picture_file", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Prašome įkelti nuotrauką")
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $profilePictureFile;

    /**
     * @return mixed
     */
    public function getProfilePictureFile()
    {
        return $this->profilePictureFile;
    }

    /**
     * @param mixed $profilePictureFile
     */
    public function setProfilePictureFile($profilePictureFile)
    {
        $this->profilePictureFile = $profilePictureFile;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param string $profilePicture
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    /**
     * @return mixed
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * @param mixed $google_id
     */
    public function setGoogleId($google_id)
    {
        $this->google_id = $google_id;
    }

    /**
     * @return mixed
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * @param mixed $google_access_token
     */
    public function setGoogleAccessToken($google_access_token)
    {
        $this->google_access_token = $google_access_token;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
