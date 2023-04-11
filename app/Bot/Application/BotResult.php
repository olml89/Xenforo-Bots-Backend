<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application;

use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Common\Domain\JsonSerializable;

final class BotResult extends JsonSerializable
{
    public readonly string $id;
    public readonly int $user_id;
    public readonly string $name;
    public readonly string $registered_at;
    public readonly ?SubscriptionResult $subscription;

    public function __construct(Bot $bot) {
        $this->id = (string)$bot->id();
        $this->user_id = $bot->userId()->toInt();
        $this->name = (string)$bot->name();
        $this->registered_at = $bot->registeredAt()->format('c');
        $this->subscription = $bot->isSubscribed()
            ? new SubscriptionResult($bot->subscription())
            : null;
    }
}
