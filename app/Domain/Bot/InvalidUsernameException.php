<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\Bot;

use olml89\XenforoBots\Domain\ValueObjects\ValueObjectException;

final class InvalidUsernameException extends ValueObjectException
{
    public function __construct(string $username)
    {
        parent::__construct(
            sprintf(
                'Username has to be 50 characters length or fewer, \'%s\' provided with <%s> characters',
                $username,
                strlen($username),
            )
        );
    }
}
