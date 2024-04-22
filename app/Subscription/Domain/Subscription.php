<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final class Subscription
{
    private bool $isActive = false;
    private UnixTimestamp $activationChangedAt;

    public function __construct(
        private readonly Uuid $subscriptionId,
        private readonly UnixTimestamp $subscribedAt,
        private readonly Bot $bot,
    ) {
        $this->activationChangedAt = clone $this->subscribedAt;
    }

    public function subscriptionId(): Uuid
    {
        return $this->subscriptionId;
    }

    public function subscribedAt(): UnixTimestamp
    {
        return $this->subscribedAt;
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->activationChangedAt = UnixTimestamp::now();
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->activationChangedAt = UnixTimestamp::now();
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function activationChangedAt(): UnixTimestamp
    {
        return $this->activationChangedAt;
    }

    public function bot(): Bot
    {
        return $this->bot;
    }
}
