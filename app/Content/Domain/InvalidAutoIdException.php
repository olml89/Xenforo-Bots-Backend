<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidAutoIdException extends ValueObjectException
{
    public function __construct(int $id)
    {
        parent::__construct('Must be a positive integer');
    }
}
