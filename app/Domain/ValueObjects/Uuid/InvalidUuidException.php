<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects\Uuid;

use olml89\XenforoBots\Domain\ValueObjects\ValueObjectException;

final class InvalidUuidException extends ValueObjectException
{
    public function __construct(string $uuid)
    {
        parent::__construct(
            sprintf(
                'Must represent a valid UUID, <%s> provided',
                $uuid,
            )
        );
    }
}
