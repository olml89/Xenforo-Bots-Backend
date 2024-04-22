<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests;

use Database\Factories\SubscriptionFactory;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotSubscriptionCreationData;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotSubscriptionData;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create\XenforoBotSubscriptionCreationDataCreator;

final class XenforoBotSubscriptionDataCreator
{
    private ?string $bot_subscription_id = null;
    private ?string $webhook = null;
    private ?string $platform_api_key = null;
    private bool $is_active = false;
    private ?int $subscribed_at = null;
    private ?XenforoBotSubscriptionCreationData $xenforoBotSubscriptionCreationData = null;
    private ?Subscription $subscription = null;

    public function __construct(
        private readonly XenforoBotSubscriptionCreationDataCreator $xenforoBotSubscriptionCreationDataCreator,
        private readonly SubscriptionFactory $subscriptionFactory,
    ) {}

    public function botSubscriptionId(string $bot_subscription_id): self
    {
        $this->bot_subscription_id = $bot_subscription_id;

        return $this;
    }

    public function webhook(string $webhook): self
    {
        $this->webhook = $webhook;

        return $this;
    }

    public function platformApiKey(string $platform_api_key): self
    {
        $this->platform_api_key = $platform_api_key;

        return $this;
    }

    public function isActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function subscribedAt(int $subscribed_at): self
    {
        $this->subscribed_at = $subscribed_at;

        return $this;
    }

    public function xenforoBotSubscriptionCreationData(XenforoBotSubscriptionCreationData $xenforoBotSubscriptionCreationData): self
    {
        $this->xenforoBotSubscriptionCreationData = $xenforoBotSubscriptionCreationData;

        return $this;
    }

    public function subscription(Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function reset(): void
    {
        $this->bot_subscription_id = null;
        $this->webhook = null;
        $this->platform_api_key = null;
        $this->is_active = false;
        $this->subscribed_at = null;
        $this->xenforoBotSubscriptionCreationData = null;
        $this->subscription = null;
    }

    public function create(): XenforoBotSubscriptionData
    {
        $this->xenforoBotSubscriptionCreationData ??= $this->xenforoBotSubscriptionCreationDataCreator->create();
        $this->subscription ??= $this->subscriptionFactory->create();

        $xenforoBotSubscriptionData = new XenforoBotSubscriptionData(
            bot_subscription_id: $this->bot_subscription_id ?? (string)$this->subscription->subscriptionId(),
            webhook: $this->webhook ?? $this->xenforoBotSubscriptionCreationData->webhook,
            platform_api_key: $this->platform_api_key ?? $this->xenforoBotSubscriptionCreationData->platform_api_key,
            is_active: $this->is_active ?? $this->subscription->isActive(),
            subscribed_at: $this->subscribed_at ?? $this->subscription->subscribedAt()->timestamp()
        );
        $this->reset();

        return $xenforoBotSubscriptionData;
    }
}
