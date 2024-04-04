<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Reply\Domain;

use Exception;
use Throwable;

final class ReplyStorageException extends Exception
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }
}

