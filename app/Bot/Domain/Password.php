<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class Password implements StringValueObject
{
    private function __construct(
        private string $password,
    ) {}

    /**
     * @throws InvalidPasswordException
     */
    public static function create(string $password): self
    {
        self::ensureIsNotEmpty($password);

        return new self($password);
    }

    /**
     * @throws InvalidPasswordException
     */
    private static function ensureIsNotEmpty(string $password): void
    {
        if (strlen($password) === 0) {
            throw new InvalidPasswordException();
        }
    }

    public function value(): string
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
