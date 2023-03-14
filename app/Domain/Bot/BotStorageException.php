<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\Bot;

use Exception;
use Throwable;

final class BotStorageException extends Exception
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }
}
