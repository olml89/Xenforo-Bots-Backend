<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\ApiErrorResponseData;

final class XenforoApiException extends Exception
{
    private readonly string $errorCode;

    public function __construct(GuzzleException $guzzleException)
    {
        $apiErrorResponseData = (!($guzzleException instanceof RequestException))
            ? ApiErrorResponseData::genericError()
            : ApiErrorResponseData::fromResponse($guzzleException->getResponse());

        $this->errorCode = $apiErrorResponseData->errorCode;

        parent::__construct(
            message: $apiErrorResponseData->message,
            code: $apiErrorResponseData->httpCode,
            previous: $guzzleException,
        );
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
