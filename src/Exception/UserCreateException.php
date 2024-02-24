<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserCreateException extends Exception
{
    public function __construct(
        string     $message = null,
        int        $code = Response::HTTP_UNPROCESSABLE_ENTITY,
        ?Throwable $previous = null,
    )
    {
        parent::__construct($message, $code, $previous);
    }
}