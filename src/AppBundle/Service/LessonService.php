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
    private $lessonRepository;

    /**
     * @var TimeService
     */
    private $timeService;

    /**
     * LessonService constructor.
     *
     * @param LessonRepository $lessonRepository
     * @param TimeService $timeService
     */
    public function __construct(LessonRepository $lessonRepository, TimeService $timeService)
    {
        $this->lessonRepository = $lessonRepository;
        $this->timeService = $timeService;
    }

    public function getCurrentLesson(User $user)
    {
        try {
            return $this->lessonRepository->findUserLessonByTime($user, $this->timeService->getCurrentTime());
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
        return $this->lessonRepository->findNextUserLessonById($user, $lesson->getId());
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
        return $this->lessonRepository->findPrevUserLessonById($user, $lesson->getId());
    }

    /**
     * Finds future lessons from current time
     *
     * @param  User $user
     * @param  int $offset
     * @param  int $limit
     * @return array
     */
    public function getUserLessonsFromNow(User $user, $offset, $limit)
    {
        return $this->lessonRepository->findUserLessonsFromDate(
            $user,
            $this->timeService->getCurrentDate(),
            $offset,
            $limit
        );
    }

    /**
     * Checks if user has lessons from current time
     *
     * @param  User $user
     * @param  int $offset
     * @param  int $limit
     * @return bool
     */
    public function hasUserLessonsFromNow(User $user, $offset, $limit)
    {
        return count($this->getUserLessonsFromNow($user, $offset, $limit)) > 0;
    }

    /**
     * Checks if user has lessons at given time interval
     *
     * @param  User $user
     * @param  \DateTime $start_time
     * @param  \DateTime $end_time
     * @return bool
     */
    public function hasUserLessonAt(User $user, $start_time, $end_time)
    {
        return $this->lessonRepository->countUserLessonsAt($user, $start_time, $end_time) > 0;
    }
}
