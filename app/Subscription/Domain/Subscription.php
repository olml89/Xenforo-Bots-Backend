<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Domain;

use DateTimeImmutable;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;

final class Subscription
{
    public function __construct(
        private readonly Uuid $id,
        private readonly Bot $bot,
        private readonly Url $xenforoUrl,
        private readonly DateTimeImmutable $subscribedAt,
    ) {}

    public function id(): Uuid
    {
        return $this->id;
    }

    public function bot(): Bot
    {
        return $this->bot;
    }

    public function xenforoUrl(): Url
    {
        return $this->xenforoUrl;
    }

    public function subscribedAt(): DateTimeImmutable
    {
        return $this->subscribedAt;
    }
}
