<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use olml89\XenforoBotsBackend\Content\Domain\AutoId;

final class AutoIdFactory
{
    public function create(): AutoId
    {
        return AutoId::create(fake()->numberBetween(1, 100));
    }
}
