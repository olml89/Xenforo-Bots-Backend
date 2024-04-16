<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions;

use GuzzleHttp\Exception\GuzzleException;

final class XenforoApiConnectionException extends XenforoApiException
{
    public static function create(GuzzleException $guzzleException): self
    {
        return new self(
            message: $guzzleException->getMessage(),
            code: $guzzleException->getCode(),
            previous: $guzzleException,
        );
    }
}
