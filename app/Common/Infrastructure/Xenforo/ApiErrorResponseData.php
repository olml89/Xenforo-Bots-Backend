<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class ApiErrorResponseData extends ApiResponseData
{
    private function __construct(
        public readonly int $httpCode,
        public readonly string $errorCode,
        public readonly string $message,
    ) {}

    public static function fromResponse(ResponseInterface $response): self
    {
        $json = self::jsonDecode($response);
        $error = $json['errors'][0];

        return new self(
            httpCode: $response->getStatusCode(),
            errorCode: $error['code'],
            message: $error['message'],
        );
    }

    public static function fromGuzzleException(GuzzleException $e): self
    {
        return new self(
            httpCode: 500,
            errorCode: 'guzzle_exception',
            message: $e->getMessage(),
        );
    }
}
