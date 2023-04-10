<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Domain;

use olml89\XenforoBots\Bot\Domain\Bot;

interface SubscriptionRemover
{
    /**
     * @throws SubscriptionRemovalException
     */
    public function remove(Bot $bot): void;
}
