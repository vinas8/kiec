<?php
namespace AppBundle\Controller\Rest;

use AppBundle\Entity\AccessToken;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class TokensController
 *
 * @package AppBundle\Controller\Rest
 */
class TokensController extends BaseRestController
{
    /**
     * Delete token
     *
     * @Rest\View()
     *
     * @ApiDoc(
     *     section="OAuth",
     *     description="Removed access token",
     *     authentication=true,
     *     statusCodes={
     *         202="Returned when access token is removed",
     *         403="Returned when the user is not authorized",
     *         404="Returned when token is not found",
     *         422="Returned on validation error",
     *         500="Returned on any error"
     *     }
     * )
     *
     * @param  AccessToken $accessToken
     * @return \FOS\RestBundle\View\View
     */
    public function deleteTokenAction(AccessToken $accessToken)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($accessToken);
        $em->flush();

        return $this->buildDeletedResponse('Token deleted.');
    }
}
