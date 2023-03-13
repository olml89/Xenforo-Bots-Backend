<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects;

final class DatetimeImmutable extends \DateTimeImmutable
{

    public function __construct(string $datetime = "now", ?DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
    }
}
