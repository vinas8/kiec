<?php
namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Activity;
use AppBundle\Repository\ActivityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class ActivitiesController
 *
 * @package AppBundle\Controller\Rest
 */
class ActivitiesController extends BaseRestController
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
     *              "name": "Ball throwing",
     *              "best_result_determination": "max",
     *              "units": "m",
     *              "origin": "native",
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
     * @Rest\View(serializerGroups={"activity_full", "user_short", "list"})
     *
     * @ApiDoc(
     *     section="Activities",
     *     description="The list of all activities",
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
    public function getActivitiesAction()
    {
        /* @var $activitiesRepository ActivityRepository */
        $activitiesRepository = $this->getDoctrine()->getRepository(Activity::class);

        /* @var $activities Activity[] */
        $activities = $activitiesRepository->findAll();

        return $this->buildResponse($activities);
    }
}
