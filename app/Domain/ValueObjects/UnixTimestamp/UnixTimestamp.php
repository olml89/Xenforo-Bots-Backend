<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects\UnixTimestamp;

use DateTimeImmutable;

final class UnixTimestamp
{
    public readonly DateTimeImmutable $value;

    public function __construct(int $timestamp)
    {
        $dateTime = DateTimeImmutable::createFromFormat('U', (string)$timestamp);

        if (!$dateTime) {
            throw new InvalidUnixTimestampException($timestamp);
        }

        $this->value = $dateTime;
    }
}
