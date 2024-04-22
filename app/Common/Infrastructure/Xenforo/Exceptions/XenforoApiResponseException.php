<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\ApiErrorResponseData;

abstract class XenforoApiResponseException extends XenforoApiException
{
    private readonly string $errorCode;
    private readonly array $params;
    private readonly ?array $debug;

    public function __construct(RequestException $guzzleRequestException)
    {
        $apiErrorResponseData = ApiErrorResponseData::fromResponse(
            $guzzleRequestException->getResponse()
        );

        $this->errorCode = $apiErrorResponseData->errorCode;
        $this->params = $apiErrorResponseData->params;
        $this->debug = $apiErrorResponseData->debug;

        parent::__construct(
            message: $apiErrorResponseData->message,
            code: $apiErrorResponseData->httpCode,
            previous: $guzzleRequestException
        );
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getParam(string $param): mixed
    {
        return $this->getParams()[$param] ?? throw new InvalidArgumentException(
            sprintf(
                'Param \'%s\' does not exist',
                $param,
            )
        );
    }

    public function getDebug(): ?array
    {
        return $this->debug;
    }
}
