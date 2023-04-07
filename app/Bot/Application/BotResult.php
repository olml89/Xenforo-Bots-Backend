<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application;

use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Common\Domain\JsonSerializable;

final class BotResult extends JsonSerializable
{
    public readonly string $id;
    public readonly int $userId;
    public readonly string $name;
    public readonly string $registeredAt;

    public function __construct(Bot $bot) {
        $this->id = (string)$bot->id();
        $this->userId = $bot->userId()->toInt();
        $this->name = (string)$bot->name();
        $this->registeredAt = $bot->registeredAt()->format('c');
    }
}
