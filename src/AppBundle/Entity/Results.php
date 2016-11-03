<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Results
 *
 * @ORM\Table(name="results")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResultsRepository")
 */
class Results
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
     * @var int
     *
     * @ORM\Column(name="activity", type="integer")
     * @ORM\ManyToOne(targetEntity="Activity")
     */
    private $activity;

    /**
     * @var int
     *
     * @ORM\Column(name="studentInfo", type="integer")
     * @ORM\ManyToOne(targetEntity="StudentInfo")
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
     * @return Results
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
     * @return Results
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
     * @param integer $activity
     *
     * @return Results
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
     * @param integer $studentInfo
     *
     * @return Results
     */
    public function setStudentInfo($studentInfo)
    {
        $this->studentInfo = $studentInfo;

        return $this;
    }

    /**
     * Get studentInfo
     *
     * @return int
     */
    public function getStudentInfo()
    {
        return $this->studentInfo;
    }
}

