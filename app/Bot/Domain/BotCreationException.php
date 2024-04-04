<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use Exception;
use Throwable;

final class BotCreationException extends Exception
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }

    public static function alreadyExists(Username $username): self
    {
        return new self(
            sprintf('Bot <%s> already exists', $username)
        );
    }
}
