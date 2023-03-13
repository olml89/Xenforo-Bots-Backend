<?php declare(strict_types=1);

namespace olml89\XenforoBots\Domain\ValueObjects\Uuid;

interface UuidManager
{
    public function random(): string;
    public function isValid(string $uuid): bool;
}
