<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\UnixTimestampFactory;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Subscription\Domain\Subscription;

final class SubscriptionFactory
{
    private ?Uuid $subscriptionId;
    private ?UnixTimestamp $subscribedAt = null;
    private ?Bot $bot = null;

    public function __construct(
        private readonly UnixTimestampFactory $unixTimestampFactory,
        private readonly BotFactory $botFactory,
    ) {}

    public function subscriptionId(Uuid $subscriptionId): self
    {
        $this->subscriptionId = $subscriptionId;

        return $this;
    }

    public function subscribedAt(UnixTimestamp $subscribedAt): self
    {
        $this->subscribedAt = $subscribedAt;

        return $this;
    }

    public function bot(Bot $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    public function reset(): void
    {
        $this->subscriptionId = null;
        $this->subscribedAt = null;
        $this->bot = null;
    }

    public function create(): Subscription
    {
        $subscription = new Subscription(
            subscriptionId: $this->subscriptionId ?? Uuid::random(),
            subscribedAt: $this->subscribedAt ?? $this->unixTimestampFactory->create(),
            bot: $this->bot ?? $this->botFactory->create()
        );
        $this->reset();

        return $subscription;
    }
}
