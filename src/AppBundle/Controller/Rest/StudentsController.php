<?php
namespace AppBundle\Controller\Rest;

use AppBundle\Entity\StudentInfo;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class StudentsController
 *
 * @package AppBundle\Controller\Rest
 */
class StudentsController extends BaseRestController
{
    /**
     * Get student information
     *
     * @Rest\View(serializerGroups={"student_info_full", "class_info_short", "details"})
     *
     * @ApiDoc(
     *     section="Students",
     *     description="Student information",
     *     authentication=true,
     *     statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized",
     *         422="Returned on validation error",
     *         500="Returned on any error"
     *     }
     * )
     *
     * @param  StudentInfo $student
     * @param  Request $request
     * @return \AppBundle\Model\Response
     */
    public function getStudentsActions(StudentInfo $student, Request $request)
    {
        return $this->buildResponse($student);
    }
}
