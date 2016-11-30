<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.30
 * Time: 21.16
 */

namespace AppBundle\Form\DataTransformer;


use AppBundle\Entity\StudentInfo;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

class StudentInfoToNumberTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * Transforms an object (studentInfo) to an integer (id).
     *
     * @param  StudentInfo|null $studentInfo
     * @return int
     */
    public function transform($studentInfo)
    {
        if (null === $studentInfo) {
            return null;
        }
        return $studentInfo->getId();
    }

    /**
     * Transforms an integer (id) to an object (studentInfo).
     *
     * @param  int $studentInfoId
     * @return StudentInfo|null
     */
    public function reverseTransform($studentInfoId)
    {
        $studentInfo = $this->em
            ->getRepository(StudentInfo::class)
            ->findOneById($studentInfoId);

        return $studentInfo;
    }
}