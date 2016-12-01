<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var object
     *
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="results")
     */
    private $activity;

    /**
     * @var object
     *
     * @ORM\ManyToOne(targetEntity="StudentInfo", inversedBy="results")
     */
    private $studentInfo;


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
}
