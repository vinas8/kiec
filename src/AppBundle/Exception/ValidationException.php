<?php
namespace AppBundle\Exception;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ValidationException
 *
 * @package AppBundle\Exception
 */
class ValidationException extends HttpException
{
    /**
     * @var Form
     */
    private $form;

    /**
     * ValidationException constructor.
     *
     * @param  string $message
     * @param  \Exception|null $previous
     * @param  array $headers
     * @param  int $code
     */
    public function __construct($message, \Exception $previous = null, array $headers = [], $code = 0)
    {
        parent::__construct(422, $message, $previous, $headers, $code);
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form)
    {
        $this->form = $form;

        return $this;
    }
}
