<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application;

use olml89\XenforoBots\Common\Domain\JsonSerializable;
use olml89\XenforoBots\Subscription\Domain\Subscription;

final class SubscriptionResult extends JsonSerializable
{
    public readonly string $id;
    public readonly string $xenforo_url;
    public readonly string $subscribed_at;

    public function __construct(Subscription $subscription)
    {
        $this->id = (string)$subscription->id();
        $this->xenforo_url = (string)$subscription->xenforoUrl();
        $this->subscribed_at = $subscription->subscribedAt()->format('c');
    }
}
