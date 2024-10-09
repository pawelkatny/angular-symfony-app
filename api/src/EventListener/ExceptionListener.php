<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $statusCode = $exception->getCode();
        $message = $exception->getMessage();

        $errorData = json_decode($message, true) ?? ['message' => $message];

        if ($statusCode === 0) {
            $statusCode = $exception->getPrevious() !== null ? $exception->getPrevious()->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $resData = array_merge([
            'data' => null,
            ], $errorData);
        $response = new JsonResponse($resData, $statusCode);

        $event->setResponse($response);
    }
}
