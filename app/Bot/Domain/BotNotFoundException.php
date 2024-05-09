<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Specification;
use olml89\XenforoBotsBackend\Common\Domain\Exceptions\EntityNotFoundException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final class BotNotFoundException extends EntityNotFoundException
{
    public static function botId(Uuid $botId): self
    {
        return new self(
            sprintf('Bot with botId \'%s\' not found', $botId)
        );
    }

    public static function specification(Specification $specification): self
    {
        return new self(
            sprintf('Bot with criteria \'%s\' not found', $specification->criteria())
        );
    }
}
