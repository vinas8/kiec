<?php
namespace AppBundle\EventListener;


use AppBundle\Controller\InitializableController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class LessonControllerListener
{
    public function onInit(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof InitializableController) {
            $controller[0]->init();
        }
    }
}
