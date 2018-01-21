<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.12.8
 * Time: 01.52
 */

namespace AppBundle\Utils;

class TopResultSet
{
    private $activity;

    private $classInfo;

    private $maxResults;

    /**
     * @return mixed
     */
    public function getActivity() {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity) {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getClassInfo() {
        return $this->classInfo;
    }

    /**
     * @param mixed $classInfo
     */
    public function setClassInfo($classInfo) {
        $this->classInfo = $classInfo;
    }

    /**
     * @return mixed
     */
    public function getMaxResults() {
        return $this->maxResults;
    }

    /**
     * @param mixed $maxResults
     */
    public function setMaxResults($maxResults) {
        $this->maxResults = $maxResults;
    }
}
