<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionValidationException;

final readonly class BotSubscriber
{
    public function __construct(
        private RemoteBotSubscriber $remoteBotSubscriber,
    ) {}

    /**
     * @throws SubscriptionValidationException
     * @throws SubscriptionCreationException
     */
    public function subscribe(Bot $bot): void
    {
        $subscription = $this->remoteBotSubscriber->subscribe($bot);

        $bot->subscribe($subscription);
    }
}
