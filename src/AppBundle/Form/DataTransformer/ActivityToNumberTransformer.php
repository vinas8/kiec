<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.30
 * Time: 21.16
 */

namespace AppBundle\Form\DataTransformer;


use AppBundle\Entity\Activity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

class ActivityToNumberTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (activity) to an integer (id).
     *
     * @param  Activity|null $activity
     * @return int
     */
    public function transform($activity)
    {
        return $activity ? $activity->getId() : null;
    }

    /**
     * Transforms an integer (id) to an object (activity).
     *
     * @param  int $activityId
     * @return Activity|null
     */
    public function reverseTransform($activityId)
    {
        $activity = $this->em
            ->getRepository(Activity::class)
            ->findOneById($activityId);

        return $activity;
    }
}