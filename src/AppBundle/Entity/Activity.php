<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActivityRepository")
 */
class Activity
{
    const BEST_RESULT_DETERMINATION_MAX = 'max';
    const BEST_RESULT_DETERMINATION_MIN = 'min';

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
     * @var string
     *
     * @ORM\Column(name="bestResultDetermination", type="string", columnDefinition="ENUM('max', 'min')")
     */
    private $bestResultDetermination;

    /**
     * @var string
     *
     * @ORM\Column(name="units", type="string", length=255)
     */
    private $units;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Result", mappedBy="activity")
     */
    private $results;


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
     * @return Activity
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
     * @return Collection | Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Collection $results
     * @return Activity
     */
    public function setResults($results)
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @return string
     */
    public function getBestResultDetermination()
    {
        return $this->bestResultDetermination;
    }

    /**
     * @param string $bestResultDetermination
     */
    public function setBestResultDetermination($bestResultDetermination)
    {
        if (!in_array($bestResultDetermination, array(self::BEST_RESULT_DETERMINATION_MAX, self::BEST_RESULT_DETERMINATION_MIN))) {
            throw new \InvalidArgumentException("Invalid best result determination");
        }
        $this->$bestResultDetermination = $bestResultDetermination;
    }

    /**
     * @return string
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param string $units
     */
    public function setUnits($units)
    {
        $this->units = $units;
    }
}
