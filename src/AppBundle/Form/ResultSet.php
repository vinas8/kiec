<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.30
 * Time: 21.32
 */

namespace AppBundle\Form;


use Doctrine\Common\Collections\ArrayCollection;

class ResultSet
{
    private $results;

    /**
     * ResultSet constructor.
     */
    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param ArrayCollection $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }


}