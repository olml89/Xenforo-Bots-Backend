<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions;

use GuzzleHttp\Exception\GuzzleException;

final class XenforoApiConnectionException extends XenforoApiException
{
    public function __construct(GuzzleException $guzzleException)
    {
        parent::__construct(
            message: $guzzleException->getMessage(),
            code: $guzzleException->getCode(),
            previous: $guzzleException
        );
    }
}
