<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

abstract class XenforoApiException extends Exception
{
    protected function __construct(string $message, int $code, GuzzleException $previous)
    {
        parent::__construct($message, $code, $previous);
    }
}
