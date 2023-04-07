<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Domain;

use olml89\XenforoBots\Bot\Domain\Bot;

interface SubscriptionRetriever
{
    /**
     * @throws SubscriptionRetrievalException in case the remote XenForo is unavailable or something fails
     */
    public function get(Bot $bot): ?Subscription;
}
