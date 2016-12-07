<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * Activity
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActivityRepository")
 */
class Activity
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
     * @var string
     *
     * @ORM\Column(name="bestResultDetermination", type="BestResultDeterminationType", nullable=false)
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\BestResultDeterminationType")
     */
    private $bestResultDetermination;

    /**
     * @var string
     *
     * @ORM\Column(name="units", type="string", length=255)
     */
    private $units;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="OriginType", nullable=false)
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\OriginType")
     */
    private $origin;

    /**
     * @var object
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Result", mappedBy="activity")
     */
    private $results;

    /**
     * Activity constructor.
     * @param object $user
     */
    public function __construct(User $user, $origin)
    {
        $this->setUser($user);
        $this->setOrigin($origin);
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
        $this->bestResultDetermination = $bestResultDetermination;
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

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }


}
