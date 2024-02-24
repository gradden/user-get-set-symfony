<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseRequest
{
    private RequestStack $request;
    private ValidatorInterface $validator;
    private TranslatorInterface $translator;

    public function __construct(
        ValidatorInterface  $validator,
        RequestStack        $request,
        TranslatorInterface $translator
    )
    {
        $this->request = $request;
        $this->validator = $validator;
        $this->translator = $translator;

        $this->populate();

        if ($this->autoValidateRequest()) {
            $this->validate();
        }
    }

    public function validate(): void
    {
        $errors = $this->validator->validate($this);

        $message = [
            'message' => $this->translator->trans('errors.validation_failed'),
            'properties' => []
        ];

        foreach ($errors as $error) {
            $message['properties'][] = [
                'property' => $error->getPropertyPath(),
                'value' => $error->getInvalidValue(),
                'message' => $error->getMessage()
            ];
        }

        if (count($errors) > 0) {
            (new JsonResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY))->send();
        }
    }

    public function getRequest(): Request
    {
        return $this->request->getCurrentRequest();
    }

    protected function populate(): void
    {
        foreach ($this->getRequest()->toArray() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }

    protected function autoValidateRequest(): bool
    {
        return true;
    }
}