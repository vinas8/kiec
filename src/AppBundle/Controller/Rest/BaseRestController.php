<?php
namespace AppBundle\Controller\Rest;


use AppBundle\Exception\ValidationException;
use AppBundle\Model\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class BaseRestController
 *
 * @package AppBundle\Controller\Rest
 *
 * @Rest\Version("v1")
 */
abstract class BaseRestController extends FOSRestController
{
    /**
     * @param  mixed $data
     * @param  mixed $metadata
     * @return Response
     */
    protected function buildResponse($data = [], $metadata = [])
    {
        if ($data instanceof \Traversable) {
            $data = iterator_to_array($data);
        }

        return Response::create($data, $metadata);
    }

    /**
     * @param  mixed $data
     * @param  mixed $location
     * @param  string $message
     * @return View
     */
    protected function buildCreatedResponse($data, $location, $message)
    {
        $metadata = [
            'message' => $message,
            'code' => HttpResponse::HTTP_CREATED
        ];

        $headers = [
            'Location' => $location
        ];

        return View::create($this->buildResponse($data, $metadata), HttpResponse::HTTP_CREATED, $headers);
    }

    /**
     * @param  string $message
     * @return View
     */
    protected function buildDeletedResponse($message)
    {
        $metadata = [
            'message' => $message,
            'code' => HttpResponse::HTTP_CREATED
        ];

        return View::create($this->buildResponse([], $metadata), HttpResponse::HTTP_ACCEPTED);
    }

    /**
     * @param  Form $form
     * @return ValidationException
     */
    protected function createValidationException(Form $form)
    {
        $exception = new ValidationException($form->getErrors(true)->current()->getMessage());
        $exception->setForm($form);

        return $exception;
    }
}
