<?php

namespace App\EventSubscriber;

use App\ApiData\ApiRequest;
use App\Controller\JsonRequestInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Exception;

class InitSubscriber implements EventSubscriberInterface
{
    /**
     * @throws Exception
     */
    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof JsonRequestInterface) {
            $event->getRequest()->attributes->set('json_request', new ApiRequest($request));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
