<?php

namespace AppBundle\Service;

use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\User;
use AppBundle\Repository\ClassInfoRepository;
use Doctrine\ORM\NoResultException;

class ClassInfoService
{
    /**
     * @var ClassInfoRepository
     */
    private $classInfoRepository;

    /**
     * ClassInfoService constructor.
     *
     * @param  ClassInfoRepository $classInfoRepository
     */
    public function __construct(ClassInfoRepository $classInfoRepository) {
        $this->classInfoRepository = $classInfoRepository;
    }

    /**
     * Returns user class by id
     *
     * @param  User $user
     * @param  int $id
     * @return ClassInfo|null
     */
    public function getUserClassById(User $user, $id) {
        try {
            $class = $this->classInfoRepository->findClassesByTeacherAndClassId($user, $id);
        } catch (NoResultException $e) {
            $class = null;
        }

        return $class;
    }

    /**
     * Returns classes that are assigned to the user
     *
     * @param  User $user
     * @return ClassInfo[]|null
     */
    public function getUserClasses(User $user) {
        try {
            $classes = $this->classInfoRepository->findClassesByTeacher($user);
        } catch (NoResultException $e) {
            $classes = null;
        }

        return $classes;
    }

    /**
     * Checks if user has classes
     *
     * @param  User $user
     * @return bool
     */
    public function hasUserClasses(User $user) {
        return $this->classInfoRepository->countUserClasses($user) > 0;
    }
}
