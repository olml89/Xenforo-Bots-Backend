<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username;

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
        self::ensureItHasBetween3And50Characters($username);

        return new self($username);
    }

    /**
     * @throws InvalidUsernameException
     */
    private static function ensureItHasBetween3And50Characters(string $username): void
    {
        if (strlen($username) < 3) {
            throw InvalidUsernameException::tooShort(3, $username);
        }

        if (strlen($username) > 50) {
            throw InvalidUsernameException::tooLong(50, $username);
        }
    }

    public function equals(Username $username): bool
    {
        return $this->value() === $username->value();
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
