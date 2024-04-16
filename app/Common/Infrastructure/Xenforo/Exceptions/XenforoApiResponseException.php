<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\ApiErrorResponseData;

abstract class XenforoApiResponseException extends XenforoApiException
{
    private readonly string $errorCode;

    private function __construct(string $message, int $code, string $errorCode, GuzzleException $previous)
    {
        $this->errorCode = $errorCode;

        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous,
        );
    }

    public static function create(RequestException $guzzleRequestException): static
    {
        $apiErrorResponseData = ApiErrorResponseData::fromResponse($guzzleRequestException->getResponse());

        return new static(
            message: $apiErrorResponseData->message,
            code: $apiErrorResponseData->httpCode,
            errorCode: $apiErrorResponseData->errorCode,
            previous: $guzzleRequestException,
        );
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
