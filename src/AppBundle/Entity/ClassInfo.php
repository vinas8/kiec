<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     *
     * @ORM\OneToMany(targetEntity="StudentInfo", mappedBy="classInfo")
     */
    private $students;


    /**
     * @ORM\ManyToMany(targetEntity="User")
     */
    private $user;

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
