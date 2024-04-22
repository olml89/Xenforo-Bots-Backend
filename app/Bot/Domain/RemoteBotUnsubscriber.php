<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionRemovalException;

interface RemoteBotUnsubscriber
{
    /**
     * @throws SubscriptionRemovalException
     */
    public function unsubscribe(Bot $bot): void;
}
