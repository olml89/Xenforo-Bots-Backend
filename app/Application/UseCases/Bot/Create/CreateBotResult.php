<?php declare(strict_types=1);

namespace olml89\XenforoBots\Application\UseCases\Bot\Create;

use olml89\XenforoBots\Domain\Bot\Bot;
use olml89\XenforoBots\Domain\JsonSerializable;

final class CreateBotResult extends JsonSerializable
{
    public readonly string $id;
    public readonly int $userId;
    public readonly string $name;
    public readonly string $registeredAt;

    public function __construct(Bot $bot) {
        $this->id = $bot->id()->value;
        $this->userId = $bot->userId()->value;
        $this->name = $bot->name()->value;
        $this->registeredAt = $bot->registeredAt()->value->format('c');
    }
}
