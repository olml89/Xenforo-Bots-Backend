<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use Psr\Http\Message\ResponseInterface;

final readonly class ApiErrorResponseData extends ApiResponseData
{
    private function __construct(
        public int $httpCode,
        public string $errorCode,
        public string $message,
        public array $params = [],
        public ?array $debug = null,
    ) {}

    private static function unknownError(int $httpStatusCode): self
    {
        return new self(
            httpCode: $httpStatusCode,
            errorCode: 'unknown_error',
            message: 'Unknown error',
        );
    }

    public static function fromResponse(ResponseInterface $response): self
    {
        $json = self::jsonDecode($response);
        $error = $json['errors'][0] ?? null;

        if (is_null($error)) {
            return self::unknownError($response->getStatusCode());
        }

        return new self(
            httpCode: $response->getStatusCode(),
            errorCode: $error['code'],
            message: $error['message'],
            params: $error['params'],
            debug: $json['exception'] ?? null,
        );
    }
}
