<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Domain;

use olml89\XenforoBots\Bot\Domain\Bot;

interface SubscriptionCreator
{
    /**
     * @throws SubscriptionCreationException
     */
    public function create(Bot $bot, string $password): Subscription;
}
