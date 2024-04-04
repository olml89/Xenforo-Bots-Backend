<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp;

use DateTimeImmutable;

final class UnixTimestamp
{
    public static function toDateTimeImmutable(int $timestamp): DateTimeImmutable
    {
        $dateTime = DateTimeImmutable::createFromFormat('U', (string)$timestamp);

        if (!$dateTime) {
            throw new InvalidUnixTimestampException($timestamp);
        }

        return $dateTime;
    }
}
