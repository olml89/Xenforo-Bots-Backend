<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use Exception;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final class BotNotFoundException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function botId(Uuid $botId): self
    {
        return new self(
            sprintf('Bot with botId \'%s\' not found', $botId)
        );
    }

    public static function username(Username $username): self
    {
        return new self(
            sprintf('Bot with username \'%s\' not found', $username)
        );
    }
}
