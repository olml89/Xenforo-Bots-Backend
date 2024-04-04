<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;

interface SubscriptionRemover
{
    /**
     * @throws SubscriptionRemovalException
     */
    public function remove(Bot $bot): void;
}
