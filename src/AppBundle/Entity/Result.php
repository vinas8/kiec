<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Result
 *
 * @ORM\Table(name="result")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResultRepository")
 */
class Result
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
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     * @Assert\NotNull()
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     * @Assert\NotNull()
     */
    private $timestamp;

    /**
     * @var object
     *
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="results")
     * @Assert\NotNull()
     */
    private $activity;

    /**
     * @var object
     *
     * @ORM\ManyToOne(targetEntity="StudentInfo", inversedBy="results")
     * @Assert\NotNull()
     */
    private $studentInfo;

    /**
     * @var object
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * Result constructor.
     * @param Activity $activity
     * @param StudentInfo $studentInfo
     * @param User $user
     */
    public function __construct(Activity $activity = null, StudentInfo $studentInfo = null, User $user)
    {
        $this->setActivity($activity);
        $this->setStudentInfo($studentInfo);
        $this->setUser($user);
        $this->timestamp = new \DateTime('now', new \DateTimeZone('Europe/Vilnius'));
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
     * Set value
     *
     * @param float $value
     *
     * @return Result
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Result
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set activity
     *
     * @param Activity $activity
     *
     * @return Result
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return int
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set studentInfo
     *
     * @param StudentInfo $studentInfo
     *
     * @return Result
     */
    public function setStudentInfo($studentInfo)
    {
        $this->studentInfo = $studentInfo;

        return $this;
    }

    /**
     * Get studentInfo | StudentInfo[]
     *
     * @return int
     */
    public function getStudentInfo()
    {
        return $this->studentInfo;
    }

    /**
     * @return object
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param object $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
