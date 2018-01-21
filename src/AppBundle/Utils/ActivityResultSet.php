<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.30
 * Time: 21.32
 */

namespace AppBundle\Utils;

use Doctrine\Common\Collections\ArrayCollection;

class ActivityResultSet
{
    private $activities;

    /**
     * ActivityResultSet constructor.
     */
    public function __construct() {
        $this->activities = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getActivities() {
        return $this->activities;
    }

    /**
     * @param ArrayCollection $activities
     */
    public function setActivities($activities) {
        $this->activities = $activities;
    }
}
