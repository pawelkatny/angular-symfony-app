<?php

namespace App\EventSubscriber;

use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    public const CONTENT_TYPE = 'application/json';

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $contentTypeHeader = $request->headers->get('Content-Type');
        $acceptHeader = $request->headers->get('Accept');

        if (empty($contentTypeHeader) || $contentTypeHeader !== self::CONTENT_TYPE || empty($acceptHeader) || !str_contains($acceptHeader, self::CONTENT_TYPE)) {
            throw new Exception(Response::$statusTexts[Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
