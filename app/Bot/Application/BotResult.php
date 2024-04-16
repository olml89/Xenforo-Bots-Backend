<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;
use Stringable;

final class BotResult extends JsonSerializable implements Stringable
{
    public readonly string $bot_id;
    public readonly string $api_key;
    public readonly string $username;
    public readonly string $registered_at;
    //public readonly ?SubscriptionResult $subscription;

    public function __construct(Bot $bot) {
        $this->bot_id = (string)$bot->botId();
        $this->api_key = (string)$bot->apiKey();
        $this->username = (string)$bot->username();
        $this->registered_at = $bot->registeredAt()->toOutput();
        /*
        $this->subscription = $bot->isSubscribed()
            ? new SubscriptionResult($bot->subscription())
            : null;
        */
    }

    public function __toString(): string
    {
       return sprintf(
           '%s%s',
           json_encode($this, JSON_PRETTY_PRINT),
           PHP_EOL,
       );
    }
}
