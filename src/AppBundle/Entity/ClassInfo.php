<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ClassInfo
 *
 * @ORM\Table(name="class_info")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClassInfoRepository")
 */
class ClassInfo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"class_info_short", "class_info_full"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Groups({"class_info_short", "class_info_full"})
     */
    private $name;

    /**
     *
     * @ORM\OneToMany(targetEntity="StudentInfo", mappedBy="classInfo")
     */
    private $students;


    /**
     * @ORM\ManyToMany(targetEntity="User")
     *
     * @Serializer\Groups({"user_short"})
     */
    private $user;

    /**
     * Result constructor.
     * @param mixed $user
     */
    public function __construct(array $user)
    {
        $this->setUser($user);
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return ClassInfo
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


    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @return ClassInfo
     */
    public function setStudents($students)
    {
        $this->students = $students;
        return $this;
    }
}
