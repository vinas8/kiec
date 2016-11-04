<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentInfo
 *
 * @ORM\Table(name="student_info")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentInfoRepository")
 */
class StudentInfo
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="ClassInfo")
     */
    private $classInfo;

    /**
     * @var datetime
     *
     * @ORM\Column(name="birthDate", type="datetime", length=255)
     */
    private $birthDate;


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
     * @return StudentInfo
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

    /**
     * Set classInfo
     *
     * @param integer $classInfo
     *
     * @return StudentInfo
     */
    public function setClassInfo($classInfo)
    {
        $this->classInfo = $classInfo;

        return $this;
    }

    /**
     * Get classInfo
     *
     * @return int
     */
    public function getClassInfo()
    {
        return $this->classInfo;
    }

    /**
     * Set birthDate
     *
     * @param string $birthDate
     *
     * @return StudentInfo
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }
}

