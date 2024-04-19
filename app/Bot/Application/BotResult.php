<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;

final class BotResult extends JsonSerializable
{
    public readonly string $bot_id;
    public readonly string $api_key;
    public readonly string $username;
    public readonly SubscriptionResult $subscription;

    public function __construct(Bot $bot) {
        $this->bot_id = (string)$bot->botId();
        $this->api_key = (string)$bot->apiKey();
        $this->username = (string)$bot->username();
        $this->subscription = new SubscriptionResult($bot->subscription());
    }
}
