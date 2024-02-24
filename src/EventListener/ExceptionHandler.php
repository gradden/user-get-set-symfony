<?php

namespace App\EventListener;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ExceptionHandler
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        $response = $this->createApiResponse($exception);
        $event->setResponse($response);
    }

    private function createApiResponse(Exception|Throwable $exception): JsonResponse
    {
        $statusCode = ($exception->getCode() == 0 || $exception->getCode() === null) ?
            Response::HTTP_INTERNAL_SERVER_ERROR : $exception->getCode();
        $errors = [];

        $message = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        if ((bool)$_ENV['APP_DEBUG_EXCEPTION']) {
            $message['file'] = $exception->getFile();
            $message['line'] = $exception->getLine();
            $message['trace'] = $exception->getTrace();
        }

        return new JsonResponse($message, $statusCode, $errors);
    }
}