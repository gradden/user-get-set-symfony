<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UserCreateRequest extends BaseRequest
{
    #[NotBlank()]
    #[Type('string')]
    protected string $firstName;

    #[NotBlank()]
    #[Type('string')]
    protected string $lastName;

    #[NotBlank()]
    #[Type('string')]
    #[Email()]
    protected string $email;

    #[NotBlank()]
    #[Type('string')]
    protected string $password;
}