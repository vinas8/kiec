<?php
/**
 * Created by PhpStorm.
 * User: zn
 * Date: 16.11.7
 * Time: 12.21
 */

namespace AppBundle\Service;


use AppBundle\Entity\Lesson;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class LessonService
{

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getCurrentLesson()
    {
        $now = new \DateTime();
        $now->format("Y-m-d H:m");
        $now->setTimezone(new \DateTimeZone('Europe/Vilnius'));

        try {
            return $this->em->getRepository("AppBundle:Lesson")->findLessonByTime($now);
        }
        catch (NonUniqueResultException $e) {
            var_dump("Jūsų pamokų laikai dubliuojasi, patikrinkite pamokų tvarkaraštį <br><br>" . $e);die;
        }
        catch (NoResultException $e) {
            var_dump("Šiuo metu nėra pamokos <br><br>" . $e); die;
        }
    }
}