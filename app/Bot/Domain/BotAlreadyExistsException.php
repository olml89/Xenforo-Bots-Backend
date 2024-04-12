<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use Exception;

final class BotAlreadyExistsException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function username(Username $username): self
    {
        return new self(
            sprintf('Bot with username \'%s\' already exists', $username)
        );
    }
}
