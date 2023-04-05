<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Domain;

use olml89\XenforoBots\Subscription\Domain\Subscription;

interface BotSubscriber
{
    /**
     * @throws BotSubscriptionException
     */
    public function subscribe(Bot $bot, string $password): Subscription;
}
