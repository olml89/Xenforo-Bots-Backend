<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Xenforo;

use Psr\Http\Message\ResponseInterface;

abstract class ApiResponseData
{
    abstract protected static function fromResponse(ResponseInterface $response): self;

    protected static function jsonDecode(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
