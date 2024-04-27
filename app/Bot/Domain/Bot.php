<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final readonly class Bot
{
    private Subscription $subscription;

    public function __construct(
        private Uuid $botId,
        private ApiKey $apiKey,
        private Username $username
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

    public function subscribe(Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function subscription(): Subscription
    {
        return $this->subscription;
    }

    public function isActive(): bool
    {
        return $this->subscription->isActive();
    }

    public function activate(): void
    {
        $this->subscription->activate();
    }

    public function deactivate(): void
    {
        $this->subscription->deactivate();
    }
}
