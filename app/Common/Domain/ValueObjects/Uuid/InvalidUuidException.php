<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidUuidException extends ValueObjectException
{
    public function __construct(string $uuid)
    {
        parent::__construct(
            sprintf(
                'Must represent a valid Uuid, \'%s\' provided',
                $uuid,
            )
        );
    }
}
