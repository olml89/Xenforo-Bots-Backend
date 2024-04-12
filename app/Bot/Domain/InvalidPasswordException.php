<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidPasswordException extends ValueObjectException
{
    public function __construct()
    {
        parent::__construct('Password cannot be empty');
    }
}
