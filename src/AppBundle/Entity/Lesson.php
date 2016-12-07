<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 16.11.7
 * Time: 13.00
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonRepository")
 */
class Lesson
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $startTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="ClassInfo")
     */
    private $class_info;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set classInfo
     *
     * @param \AppBundle\Entity\ClassInfo $classInfo
     *
     * @return Lesson
     */
    public function setClassInfo(\AppBundle\Entity\ClassInfo $classInfo = null)
    {
        $this->class_info = $classInfo;

        return $this;
    }

    /**
     * Get classInfo
     *
     * @return \AppBundle\Entity\ClassInfo
     */
    public function getClassInfo()
    {
        return $this->class_info;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Lesson
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return Lesson
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  User $user
     * @return Lesson
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
