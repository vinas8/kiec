<?php
namespace AppBundle\Controller\Rest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class UsersController
 *
 * @package AppBundle\Controller\Rest
 */
class UsersController extends BaseRestController
{
    /**
     * Get current user information
     *
     * ### Minimal Response ###
     *
     *     {
     *       "data": [
     *          {
     *              "email": "mail@example.com",
     *              "enabled": true,
     *              "id": 1,
     *              "last_login": "2017-01-01T100:00:00+0000",
     *              "name": "john",
     *              "roles": [
     *                  "ROLE_TEACHER"
     *              ],
     *              "username": "john"
     *          }
     *       ],
     *      "metadata": {
     *          "code": 200,
     *          "message": ""
     *       }
     *     }
     *
     * @Rest\View(serializerGroups={"user_full", "details"})
     *
     * @ApiDoc(
     *     section="User",
     *     description="Current user information",
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
    public function getUserCurrentAction()
    {
        $user = $this->getUser();

        return $this->buildResponse($user);
    }
}
