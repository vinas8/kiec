<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 12/2/16
 * Time: 12:38 PM
 */

namespace AppBundle\Service;

class TimeService
{
    public function getCurrentTime()
    {
        $now = new \DateTime();
        $now->format("Y-m-d H:m");
        $now->setTimezone(new \DateTimeZone('Europe/Vilnius'));
        return $now;
    }
}
