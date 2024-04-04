<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;

interface SubscriptionRetriever
{
    /**
     * @throws SubscriptionRetrievalException in case the remote XenForo is unavailable or something fails
     */
    public function get(Bot $bot): ?Subscription;
}
