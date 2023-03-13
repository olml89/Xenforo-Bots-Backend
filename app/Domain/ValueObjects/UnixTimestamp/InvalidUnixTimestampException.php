<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects\UnixTimestamp;

use olml89\XenforoBots\Domain\ValueObjects\ValueObjectException;

final class InvalidUnixTimestampException extends ValueObjectException
{
    public function __construct(int $timestamp)
    {
        parent::__construct(
            sprintf(
                'Must represent a valid Unix timestamp, <%s> provided',
                $timestamp,
            )
        );
    }
}
