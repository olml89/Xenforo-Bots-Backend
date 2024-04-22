<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp;

use DateTimeImmutable;
use DateTimeZone;

final readonly class UnixTimestamp
{
    private const string OUTPUT_FORMAT = 'c';

    public function __construct(
        private DateTimeImmutable $dateTime,
    ) {}

    public static function now(): self
    {
        return new self(new DateTimeImmutable());
    }

    /**
     * @throws InvalidUnixTimestampException
     */
    public static function create(int $timestamp): self
    {
        $unixTimestamp = self::createFromFormat('U', (string)$timestamp);

        $validDate = checkdate(
            month: (int)$unixTimestamp->format('m'),
            day: (int)$unixTimestamp->format('d'),
            year: (int)$unixTimestamp->format('Y'),
        );

        if (!$validDate) {
            throw InvalidUnixTimestampException::invalid();
        }

        return $unixTimestamp;
    }

    /**
     * @throws InvalidUnixTimestampException
     */
    public static function createFromFormat(string $format, string $datetime, ?DateTimeZone $timezone = null): self
    {
        $dateTime = DateTimeImmutable::createFromFormat($format, $datetime, $timezone);

        if (!$dateTime) {
            throw InvalidUnixTimestampException::format($format, $datetime);
        }

        return new self($dateTime);
    }

    public function format(string $format): string
    {
        return $this->dateTime->format($format);
    }

    public function timestamp(): int
    {
        return (int)$this->format('U');
    }

    public function toOutput(): string
    {
        return $this->format(self::OUTPUT_FORMAT);
    }
}
