<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Exceptions\EntityAlreadyExistsException;

final class BotAlreadyExistsException extends EntityAlreadyExistsException
{
    public static function bot(Bot $bot): self
    {
        return new self(
            sprintf('Bot with username \'%s\' already exists (id: %s)',
                $bot->username(),
                $bot->botId(),
            )
        );
    }
}
