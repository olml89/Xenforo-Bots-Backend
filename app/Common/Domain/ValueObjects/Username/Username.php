<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class Username implements StringValueObject
{
    public const int MIN_LENGTH = 3;
    public const int MAX_LENGTH = 50;

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
        if (strlen($username) < self::MIN_LENGTH) {
            throw InvalidUsernameException::tooShort($username);
        }

        if (strlen($username) > self::MAX_LENGTH) {
            throw InvalidUsernameException::tooLong($username);
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
