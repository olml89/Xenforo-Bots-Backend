<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Exceptions;

use Exception;
use Throwable;

abstract class EntityException extends Exception
{
    protected function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }

    public static function fromException(Throwable $exception): static
    {

        return new static(
            message: $exception->getMessage(),
            previous: $exception,
        );
    }
}
