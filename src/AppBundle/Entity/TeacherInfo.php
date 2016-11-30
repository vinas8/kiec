<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherInfo
 *
 * @ORM\Table(name="teacher_info")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeacherInfoRepository")
 */
class TeacherInfo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}

