<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Domain;

use DateTimeImmutable;
use olml89\XenforoBots\Common\Domain\ValueObjects\AutoId\AutoId;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Password;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBots\Subscription\Domain\Subscription;

final class Bot
{
    private ?Subscription $subscription = null;

    public function __construct(
        private readonly Uuid $id,
        private readonly AutoId $userId,
        private readonly Username $name,
        private readonly Password $password,
        private readonly DateTimeImmutable $registeredAt,
    ) {}

    public function id(): Uuid
    {
        return $this->id;
    }

    public function userId(): AutoId
    {
        return $this->userId;
    }

    public function name(): Username
    {
        return $this->name;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function registeredAt(): DateTimeImmutable
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
