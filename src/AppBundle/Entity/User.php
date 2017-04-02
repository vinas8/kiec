<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

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
     *
     * @Serializer\Groups({"details", "list"})
     */
    protected $id;

    /**
 * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
*/
    private $google_id;

    /**
 * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
*/
    private $google_access_token;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="This value should not be blank.")
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="Name is too short",
     *     maxMessage="Name is too long",
     * )
     *
     * @Serializer\Groups({"details"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", nullable=true)
     * @Assert\File(mimeTypes={ "image/*" })
     *
     * @Serializer\Groups({"details"})
     */
    private $profilePicture;


    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=4096)
     */
    protected $plainPassword;


    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     *
     * @Serializer\Groups({"me"})
     */
    protected $email;

    /**
     * @var StudentInfo
     *
     * @ORM\OneToOne(targetEntity="StudentInfo")
     */
    private $studentInfo;


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

    /**
     * @return StudentInfo
     */
    public function getStudentInfo()
    {
        return $this->studentInfo;
    }

    /**
     * @param StudentInfo $studentInfo
     */
    public function setStudentInfo($studentInfo)
    {
        $this->studentInfo = $studentInfo;
    }




}
