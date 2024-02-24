<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserNotFoundException extends Exception
{
    public function __construct(
        string $message = null,
        int $code = Response::HTTP_NOT_FOUND,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}