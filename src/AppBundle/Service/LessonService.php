<?php
namespace AppBundle\Service;

use AppBundle\Exception\LessonException;
use AppBundle\Repository\LessonRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class LessonService
{
    /**
     * @var LessonRepository
     */
    private $repository;

    /**
     * LessonService constructor.
     *
     * @param LessonRepository $repository
     */
    public function __construct(LessonRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCurrentLesson()
    {
        $now = new \DateTime();
        $now->format("Y-m-d H:m");
        $now->setTimezone(new \DateTimeZone('Europe/Vilnius'));

        try {
            return $this->repository->findLessonByTime($now);
        }
        catch (NonUniqueResultException $e) {
            throw new LessonException("Jūsų pamokų laikai dubliuojasi, patikrinkite pamokų tvarkaraštį");
        }
        catch (NoResultException $e) {
            throw new LessonException("Šiuo metu nėra pamokos");
        }
    }

    /**
     * Finds next lesson by given id
     *
     * @param  mixed $id
     * @return mixed
     */
    public function getNext($id)
    {
        return $this->repository->findNextLesson($id);
    }

    /**
     * Finds previous lesson by given id
     *
     * @param  mixed $id
     * @return mixed
     */
    public function getPrev($id)
    {
        return $this->repository->findPrevLesson($id);
    }

    /**
     * Finds lesson by given id
     *
     * @param  mixed $id
     * @return mixed
     */
    public function getLesson($id)
    {
        return $this->repository->findLesson($id);
    }
}
