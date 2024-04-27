<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;

final class UuidFactory
{
    public function create(): Uuid
    {
        return Uuid::create(fake()->uuid());
    }
}
