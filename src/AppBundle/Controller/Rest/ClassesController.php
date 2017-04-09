<?php
namespace AppBundle\Controller\Rest;

use AppBundle\Entity\ClassInfo;
use AppBundle\Repository\ClassInfoRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;


/**
 * Class ClassesController
 *
 * @package AppBundle\Controller\Rest
 */
class ClassesController extends BaseRestController
{
    /**
     * Get classes list
     *
     * ### Minimal Response ###
     *
     *     {
     *       "data": [
     *          {
     *              "id": 1,
     *              "name": "5a",
     *              "students": [
     *                  {
     *                      "id": 1,
     *                      "name": "John Doe"
     *                  }
     *              ],
     *              "user": [
     *                  {
     *                      "enabled": true
     *                      "id": 1
     *                  }
     *              ]
     *          }
     *       ],
     *      "metadata": {
     *          "code": 200,
     *          "message": ""
     *       }
     *     }
     *
     * @Rest\View(serializerGroups={"list"})
     *
     * @ApiDoc(
     *     section="Classes",
     *     description="The list of classes",
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
    public function getClassesAction()
    {
        /* @var $classInfoRepository ClassInfoRepository */
        $classInfoRepository = $this->getDoctrine()->getRepository(ClassInfo::class);

        /* @var $classes ClassInfo[] */
        $classes = $classInfoRepository->findAll();

        return $this->buildResponse($classes);
    }
}
