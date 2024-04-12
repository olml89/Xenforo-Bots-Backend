<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use Exception;
use Throwable;

final class BotStorageException extends Exception
{
    public function __construct(Throwable $exception)
    {
        parent::__construct(
            message: $exception->getMessage(),
            previous: $exception,
        );
    }
}
