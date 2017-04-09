<?php
namespace AppBundle\Controller\Rest;


use AppBundle\Entity\Result;
use AppBundle\Form\ResultType;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class ResultsController
 *
 * @package AppBundle\Controller\Rest
 */
class ResultsController extends BaseRestController
{
    /**
     * Save result
     *
     * ### Minimal Response when created ###
     *
     *     {
     *       "data": [
     *          {
     *              "id": 1,
     *              "value": 10,
     *              "timestamp": "2017-01-01T100:00:00+0000",
     *              "student_info": {
     *                  "id": 3,
     *                  "name": "John Doe",
     *                  "birth_date": "2017-01-01T100:00:00+0000",
     *              },
     *              "user": {
     *                  "id": 1,
     *                  "email": "test@test.com"
     *              },
     *              "activity": {
     *                  "best_result_determination": "max",
     *                  "id": 1,
     *                  "name": "Ball throwing",
     *                  "origin": "native",
     *                  "units": "m",,
     *                  "user": {
     *                      "id": 1,
     *                      "email": "test@test.com"
     *                  },
     *              }
     *          }
     *       ],
     *      "metadata": {
     *          "code": 201,
     *          "message": "Result created."
     *       }
     *     }
     *
     * @Rest\View(serializerGroups={"result_full", "activity_short", "user_short", "student_info_short", "details"})
     *
     * @ApiDoc(
     *     section="Results",
     *     description="Save a result",
     *     input="AppBundle\Form\ResultType",
     *     parameters={
     *         {"name"="result[activity]", "dataType"="choice", "required"=true, "description"="Activity ID"},
     *         {"name"="result[studentInfo]", "dataType"="choice", "required"=true, "description"="Student info ID"}
     *     },
     *     authentication=true,
     *     statusCodes={
     *         201="Returned when result is saved",
     *         403="Returned when the user is not authorized",
     *         422="Returned on validation error",
     *         500="Returned on any error"
     *     }
     * )
     *
     * @param  Request $request
     * @return View
     */
    public function postResultsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $result = new Result();
        $result->setUser($this->getUser());

        $form = $this->createForm(ResultType::class, $result, [
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            throw $this->createValidationException($form);
        }

        $em->persist($result);
        $em->flush();

        return $this->buildCreatedResponse($result, $result->getId(), 'Result created.');
    }
}
