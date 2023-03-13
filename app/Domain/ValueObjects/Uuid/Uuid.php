<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects\Uuid;

use olml89\XenforoBots\Domain\ValueObjects\StringValueObject;

final class Uuid extends StringValueObject
{
    public function __construct(string $uuid, UuidManager $uuidManager)
    {
        $this->ensureIsAValidUuid($uuid, $uuidManager);

        parent::__construct($uuid);
    }

    public static function random(UuidManager $uuidManager): self
    {
        $uuid = $uuidManager->random();

        return new self($uuid, $uuidManager);
    }

    private function ensureIsAValidUuid(string $uuid, UuidManager $uuidManager): void
    {
        if (!$uuidManager->isValid($uuid)) {
            throw new InvalidUuidException($uuid);
        }
    }
}
