<?php
namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Lesson;
use AppBundle\Repository\LessonRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class ScheduleController
 *
 * @package AppBundle\Controller\Rest
 */
class ScheduleController extends BaseRestController
{
    /**
     * Get all lessons
     *
     * ### Minimal Response ###
     *
     *     {
     *       "data": [
     *          {
     *              "id": 1,
     *              "start_time": "2017-01-01T100:00:00+0000",
     *              "end_time": "2017-01-01T100:00:00+0000",
     *              "class_info": {
     *                  "id": 3,
     *                  "name": "9b"
     *              },
     *              "user": {
     *                  "id": 1,
     *                  "email": "test@test.com"
     *              }
     *          }
     *       ],
     *      "metadata": {
     *          "code": 200,
     *          "message": ""
     *       }
     *     }
     *
     * @Rest\View(serializerGroups={"lesson_full", "class_info_short", "user_short", "list"})
     *
     * @ApiDoc(
     *     section="Schedule",
     *     description="The list of all lessons",
     *     authentication=true,
     *     statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized",
     *         422="Returned on validation error",
     *         500="Returned on any error"
     *     }
     * )
     *
     * @return \AppBundle\Model\Response
     */
    public function getScheduleAction()
    {
        /* @var $lessonsRepository LessonRepository */
        $lessonsRepository = $this->getDoctrine()->getRepository(Lesson::class);

        /* @var $lessons Lesson[] */
        $lessons = $lessonsRepository->findAll();

        return $this->buildResponse($lessons);
    }
}
