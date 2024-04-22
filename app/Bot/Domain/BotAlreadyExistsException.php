<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use Exception;

final class BotAlreadyExistsException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function bot(Bot $bot): self
    {
        return new self(
            sprintf('Bot with id \'%s\' and username \'%s\' already exists',
                $bot->botId(),
                $bot->username(),
            )
        );
    }
}
