<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use Exception;

final class BotNotFoundException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function invalidName(Username $name): self
    {
        return new self(
            sprintf('Bot not found with name \'%s\'', $name)
        );
    }

    public static function invalidPassword(): self
    {
        return new self('Invalid password');
    }
}
