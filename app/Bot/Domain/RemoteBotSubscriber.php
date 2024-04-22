<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionValidationException;

interface RemoteBotSubscriber
{
    /**
     * @throws SubscriptionValidationException
     * @throws SubscriptionCreationException
     */
    public function subscribe(Bot $bot): Subscription;
}
