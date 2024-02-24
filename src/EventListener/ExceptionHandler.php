<?php

namespace App\EventListener;

use App\Response\BaseResponse;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ExceptionHandler
{
    private BaseResponse $response;

    public function __construct(BaseResponse $response)
    {
        $this->response = $response;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        $response = $this->createApiResponse($exception, $request);
        $event->setResponse($response);
    }

    private function createApiResponse(Exception|Throwable $exception, Request $request): Response
    {
        $statusCode = ($exception->getCode() == 0 || $exception->getCode() === null) ?
            Response::HTTP_INTERNAL_SERVER_ERROR : $exception->getCode();
        $debug = filter_var($_ENV['APP_DEBUG_EXCEPTION'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $message = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        if ($debug) {
            $message['file'] = $exception->getFile();
            $message['line'] = $exception->getLine();
            $message['trace'] = $exception->getTrace();
        }

        return $this->response->createResponse(
            data: $message,
            responseCode: $statusCode,
            responseType: $request->headers->get('Accept')
        );
    }
}