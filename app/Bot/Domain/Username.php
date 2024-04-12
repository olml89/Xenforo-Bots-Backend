<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class Username implements StringValueObject
{
    private function __construct(
        private string $username,
    ) {}

    /**
     * @throws InvalidUsernameException
     */
    public static function create(string $username): self
    {
        self::ensureItHasBetween1And50Characters($username);

        return new self($username);
    }

    /**
     * @throws InvalidUsernameException
     */
    private static function ensureItHasBetween1And50Characters(string $username): void
    {
        if (strlen($username) === 0) {
            throw InvalidUsernameException::empty();
        }

        if (strlen($username) > 50) {
            throw InvalidUsernameException::tooLong($username);
        }
    }

    public function value(): string
    {
        return $this->username;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
