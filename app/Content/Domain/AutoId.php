<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\IntValueObject;
use Stringable;

final readonly class AutoId implements IntValueObject, Stringable
{
    private function __construct(
        private int $id,
    ) {}

    /**
     * @throws InvalidAutoIdException
     */
    public static function create(int $id): self
    {
        self::ensureIsBiggerThan0($id);

        return new self($id);
    }

    /**
     * @throws InvalidAutoIdException
     */
    private static function ensureIsBiggerThan0(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidAutoIdException($id);
        }
    }

    public function equals(AutoId $autoId): bool
    {
        return $this->value() === $autoId->value();
    }

    public function value(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }
}
