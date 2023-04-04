<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp;

use DateTimeImmutable;

final class UnixTimestamp extends DateTimeImmutable
{
    public static function fromUnixTimestamp(int $timestamp): self
    {
        $dateTime = parent::createFromFormat('U', (string)$timestamp);

        if (!$dateTime) {
            throw new InvalidUnixTimestampException($timestamp);
        }

        return $dateTime;
    }
}
