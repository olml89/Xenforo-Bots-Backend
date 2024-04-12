<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;
use Ramsey\Uuid\Uuid as UuidFactory;
use Ramsey\Uuid\UuidInterface;

final readonly class Uuid implements StringValueObject
{
    private function __construct(
        private UuidInterface $uuid,
    ) {}

    /**
     * @throws InvalidUuidException
     */
    public static function create(string $uuid): self
    {
        self::ensureIsAValidUuid($uuid);

        return new self(UuidFactory::fromString($uuid));
    }

    /**
     * @throws InvalidUuidException
     */
    private static function ensureIsAValidUuid(string $uuid): void
    {
        if (!UuidFactory::isValid($uuid)) {
            throw new InvalidUuidException($uuid);
        }
    }

    public static function random(): self
    {
        return new self(UuidFactory::uuid4());
    }

    public function value(): string
    {
        return (string)$this->uuid;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
