<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", nullable=true)
     * @Assert\File(mimeTypes={ "image/*" })
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
     */
    protected $email;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="StudentInfo", mappedBy="user")
     */
    private $students;

    /**
     * @var StudentInfo
     *
     * @ORM\OneToOne(targetEntity="StudentInfo")
     */
    private $mainStudentInfo;


    public function __construct() {
        $this->students = new ArrayCollection();
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

    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);
    }

    /**
     * @return StudentInfo
     */
    public function getMainStudentInfo()
    {
        return $this->mainStudentInfo;
    }

    /**
     * @param StudentInfo $mainStudentInfo
     */
    public function setMainStudentInfo($mainStudentInfo)
    {
        $this->mainStudentInfo = $mainStudentInfo;
    }

    /**
     * @return Collection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @param Collection $students
     */
    public function setStudents($students)
    {
        $this->students = $students;
    }




}
