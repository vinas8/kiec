<?php
namespace AppBundle\Controller\Rest;


use AppBundle\Exception\ValidationException;
use AppBundle\Model\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\Form;

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
