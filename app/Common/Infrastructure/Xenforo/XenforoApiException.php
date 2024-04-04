<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class XenforoApiException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromResponse(ResponseInterface $response): self
    {
        $apiErrorResponseData = ApiErrorResponseData::fromResponse($response);

        return new self(
            sprintf(
                'HTTP %s: %s (%s)',
                $apiErrorResponseData->httpCode,
                $apiErrorResponseData->message,
                $apiErrorResponseData->errorCode,
            ),
        );
    }

    public static function fromGuzzleException(GuzzleException $e): self
    {
        return new self($e->getMessage());
    }
}
