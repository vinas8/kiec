<?php
namespace AppBundle\Service;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;
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

    public function getCurrentLesson(User $user)
    {
        try {
            return $this->repository->findUserLessonByTime($user, $this->time->getCurrentTime());
        } catch (NonUniqueResultException $e) {
            throw new LessonException("Jūsų pamokų laikai dubliuojasi, patikrinkite pamokų tvarkaraštį");
        } catch (NoResultException $e) {
            throw new LessonException("Šiuo metu nėra pamokos");
        }
    }

    /**
     * Finds next user lesson
     *
     * @param  User $user
     * @param  Lesson $lesson
     * @return Lesson|null
     */
    public function getNext(User $user, Lesson $lesson)
    {
        return $this->repository->findNextUserLessonById($user, $lesson->getId());
    }

    /**
     * Finds previous user lesson
     *
     * @param  User $user
     * @param  Lesson $lesson
     * @return Lesson|null
     */
    public function getPrev(User $user, Lesson $lesson)
    {
        return $this->repository->findPrevUserLessonById($user, $lesson->getId());
    }
}
