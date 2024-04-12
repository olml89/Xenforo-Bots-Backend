<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class ApiErrorResponseData extends ApiResponseData
{
    private function __construct(
        public int $httpCode,
        public string $errorCode,
        public string $message,
    ) {}

    public static function genericError(int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR): self
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
            return self::genericError($response->getStatusCode());
        }

        return new self(
            httpCode: $response->getStatusCode(),
            errorCode: $error['code'],
            message: $error['message'],
        );
    }
}
