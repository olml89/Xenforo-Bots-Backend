<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Subscription\Domain\Subscription;

final class Bot
{
    private ?Subscription $subscription = null;

    public function __construct(
        private readonly Uuid $botId,
        private readonly ApiKey $apiKey,
        private readonly Username $username,
        private readonly UnixTimestamp $registeredAt,
    ) {}

    public function botId(): Uuid
    {
        return $this->botId;
    }

    public function apiKey(): ApiKey
    {
        return $this->apiKey;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function registeredAt(): UnixTimestamp
    {
        return $this->registeredAt;
    }

    public function subscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function subscribe(Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function cancelSubscription(): self
    {
        $this->subscription = null;

        return $this;
    }

    public function isSubscribed(): bool
    {
        return !is_null($this->subscription);
    }

    public function reply(string $text): string
    {
        return $text;
    }
}
