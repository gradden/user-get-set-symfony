<?php

namespace App\Response;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserResponse
{
    public const MEDIA_TYPE_JSON = 'application/json';
    public const MEDIA_TYPE_YAML = 'application/x-yaml';

    private function returnAttributes(): array
    {
        return [
            'id',
            'firstName',
            'lastName',
            'email'
        ];
    }

    public function createResponse(
        User|array $user,
                   $responseCode = Response::HTTP_OK,
                   $responseType = self::MEDIA_TYPE_JSON
    ): Response
    {
        $context = [
            AbstractNormalizer::ATTRIBUTES => self::returnAttributes()
        ];

        if ($responseType === self::MEDIA_TYPE_YAML) {
            return $this->toYamlResponse($user, $responseCode, $context);
        }

        return $this->toJsonResponse($user, $responseCode, $context);
    }

    protected function toJsonResponse(User|array $user, int $responseCode, array $context): JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonData = json_decode($serializer->serialize($user, JsonEncoder::FORMAT, $context));

        return new JsonResponse($jsonData, $responseCode);
    }

    protected function toYamlResponse(User|array $user, int $responseCode, array $context): Response
    {
        $encoders = [new YamlEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $yamlData = $serializer->serialize($user, YamlEncoder::FORMAT, $context);

        return new Response($yamlData, $responseCode, ['Content-Type' => self::MEDIA_TYPE_YAML]);
    }
}