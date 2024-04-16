<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp;

use DateTimeImmutable;
use DateTimeZone;

final class UnixTimestamp extends DatetimeImmutable
{
    private const string DATABASE_FORMAT = 'Y-m-d H:i:s';
    private const string OUTPUT_FORMAT = 'c';

    /**
     * @throws InvalidUnixTimestampException
     */
    public static function create(int $timestamp): self
    {
        $dateTime = self::createFromFormat('U', (string)$timestamp);

        if (!$dateTime) {
            throw new InvalidUnixTimestampException($timestamp);
        }

        $validDate = checkdate(
            month: (int)$dateTime->format('m'),
            day: (int)$dateTime->format('d'),
            year: (int)$dateTime->format('Y'),
        );

        if (!$validDate) {
            throw new InvalidUnixTimestampException($timestamp);
        }

        return $dateTime;
    }

    public static function createFromFormat(
        string $format,
        string $datetime,
        ?DateTimeZone $timezone = null,
    ): self|false {
        return parent::createFromFormat($format, $datetime, $timezone);
    }

    public function timestamp(): int
    {
        return (int)self::format('U');
    }

    public function toOutput(): string
    {
        return self::format(self::OUTPUT_FORMAT);
    }

    public function toDatabase(): string
    {
        return self::format(self::DATABASE_FORMAT);
    }
}
