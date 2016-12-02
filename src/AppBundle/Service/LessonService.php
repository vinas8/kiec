<?php
namespace AppBundle\Service;

use AppBundle\Entity\Lesson;
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
    private $time;

    /**
     * LessonService constructor.
     *
     * @param LessonRepository $repository
     */
    public function __construct(LessonRepository $repository, TimeService $time)
    {
        $this->repository = $repository;
        $this->time = $time;
    }

    public function getCurrentLesson()
    {
        try {
            return $this->repository->findLessonByTime($this->time->getCurrentTime());
        } catch (NonUniqueResultException $e) {
            throw new LessonException("Jūsų pamokų laikai dubliuojasi, patikrinkite pamokų tvarkaraštį");
        } catch (NoResultException $e) {
            throw new LessonException("Šiuo metu nėra pamokos");
        }
    }

    /**
     * Finds next lesson
     *
     * @param  Lesson $lesson
     * @return Lesson|null
     */
    public function getNext(Lesson $lesson)
    {
        return $this->repository->findNextLessonById($lesson->getId());
    }

    /**
     * Finds previous lesson
     *
     * @param  Lesson $lesson
     * @return Lesson|null
     */
    public function getPrev(Lesson $lesson)
    {
        return $this->repository->findPrevLessonById($lesson->getId());
    }
}
