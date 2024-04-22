<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application;

use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;

final class SubscriptionResult extends JsonSerializable
{
    public readonly string $subscription_id;
    public readonly string $subscribed_at;
    public readonly bool $is_active;
    public readonly string $activation_changed_at;

    public function __construct(Subscription $subscription)
    {
        $this->subscription_id = (string)$subscription->subscriptionId();
        $this->subscribed_at = $subscription->subscribedAt()->toOutput();
        $this->is_active = $subscription->isActive();
        $this->activation_changed_at = $subscription->activationChangedAt()->toOutput();
    }
}
