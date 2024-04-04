<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;

interface SubscriptionCreator
{
    /**
     * @throws SubscriptionCreationException
     */
    public function create(Bot $bot, string $password): Subscription;
}
