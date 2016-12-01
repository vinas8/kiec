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
