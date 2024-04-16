<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use Psr\Http\Message\ResponseInterface;

abstract readonly class ApiResponseData
{
    abstract public static function fromResponse(ResponseInterface $response): self;

    protected static function jsonDecode(ResponseInterface $response): array
    {
        /**
         * Preventing to having to seek the stream response in order to use it more than once (f. ex. when testing)
         *
         * https://github.com/guzzle/psr7/issues/38
         */
        return json_decode((string)$response->getBody(), true);
    }
}
