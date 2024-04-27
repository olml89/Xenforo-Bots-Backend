<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidUsernameException extends ValueObjectException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function tooShort(string $username): self
    {
        return new self(
            sprintf(
                'Username must have at least 3 characters length,  \'%s\' provided with %s characters',
                $username,
                strlen($username),
            )
        );
    }

    public static function tooLong(string $username): self
    {
        return new self(
            sprintf(
                'Username cannot exceed 50 characters length,  \'%s\' provided with %s characters',
                $username,
                strlen($username),
            )
        );
    }
}
