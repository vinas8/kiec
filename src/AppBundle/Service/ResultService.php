<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.3
 * Time: 22.17
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class ResultService
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getLastResults() {
        $repository = $this->em->getRepository('AppBundle:Result');
        $query = $repository->createQueryBuilder('r')
            ->select('r')
            ->orderBy('r.timestamp', 'DESC')
            ->getQuery();
        $results = $query->getResult();

        return $results;
    }
}