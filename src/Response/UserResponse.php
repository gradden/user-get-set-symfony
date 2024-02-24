<?php

namespace App\Response;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class UserResponse extends BaseResponse
{
    private function returnAttributes(): array
    {
        return [
            'id',
            'firstName',
            'lastName',
            'email'
        ];
    }

    public function sendResponse(
        User|array $user,
        int $responseCode = Response::HTTP_OK,
        string $responseType = self::MEDIA_TYPE_JSON,
    ): Response
    {
        $context = [
            AbstractNormalizer::ATTRIBUTES => self::returnAttributes()
        ];

        return $this->createResponse($user, $context, $responseCode, $responseType);
    }
}