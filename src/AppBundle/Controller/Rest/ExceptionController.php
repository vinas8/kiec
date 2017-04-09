<?php
namespace AppBundle\Controller\Rest;


use AppBundle\Exception\ValidationException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends BaseRestController
{
    /**
     * @param  Request $request
     * @param  \Exception $exception
     * @param  DebugLoggerInterface|null $logger
     * @return \FOS\RestBundle\View\View
     */
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        $handler = $this->getViewHandler();
        if ($handler->isFormatTemplating($request->getRequestFormat())) {
            return $this->get('twig.controller.exception')->showAction(
                $request,
                FlattenException::create($exception),
                $logger
            );
        }

        $code = $this->getStatusCode($exception);

        $data = [
            'code'    => $code,
            'message' => 'Whoops. Something bad happened',
        ];

        if ($exception instanceof ValidationException) {
            $data = [
                'code'    => $code,
                'message' => $exception->getMessage(),
                'errors'  => $exception->getForm(),
            ];
        } elseif ($exception instanceof HttpExceptionInterface) {
            $data = [
                'code'    => $code,
                'message' => $exception->getMessage(),
            ];
        }


        if ($this->getParameter('kernel.debug')) {
            $data['file'] = $exception->getFile();
            $data['line'] = $exception->getLine();
        }

        $response = $this->buildResponse([], $data);

        return $this->view($response, $code);

    }

    /**
     * Determines the status code to use for the response.
     *
     * @param  \Exception $exception
     * @return int
     */
    protected function getStatusCode(\Exception $exception)
    {
        $statusCode = $this->get('fos_rest.exception.codes_map')->resolveException($exception);

        // If matched
        if ($statusCode) {
            return $statusCode;
        }

        // Otherwise, default
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return 500;
    }
}
