<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\AutoId;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidAutoIdException extends ValueObjectException
{
    public function __construct(int $user_id)
    {
        parent::__construct(
            sprintf(
                'AutoId must be bigger than 0, <%s> provided',
                $user_id,
            )
        );
    }
}
