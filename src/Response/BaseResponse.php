<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseResponse
{
    protected const MEDIA_TYPE_JSON = 'application/json';
    protected const MEDIA_TYPE_YAML = 'application/x-yaml';

    public function createResponse(
        mixed $data,
        array $context = [],
        $responseCode = Response::HTTP_OK,
        $responseType = self::MEDIA_TYPE_JSON,
    ): Response
    {
        if ($responseType === self::MEDIA_TYPE_YAML) {
            return $this->toYamlResponse($data, $responseCode, $context);
        }

        return $this->toJsonResponse($data, $responseCode, $context);
    }

    private function toJsonResponse(mixed $data, int $responseCode, array $context): JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonData = json_decode($serializer->serialize($data, JsonEncoder::FORMAT, $context));

        return new JsonResponse($jsonData, $responseCode);
    }

    private function toYamlResponse(mixed $data, int $responseCode, array $context): Response
    {
        $encoders = [new YamlEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $yamlData = $serializer->serialize($data, YamlEncoder::FORMAT, $context);

        return new Response($yamlData, $responseCode, ['Content-Type' => self::MEDIA_TYPE_YAML]);
    }

}