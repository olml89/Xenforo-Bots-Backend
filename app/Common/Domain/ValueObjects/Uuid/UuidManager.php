<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Domain\ValueObjects\Uuid;

interface UuidManager
{
    public function random(): string;
    public function isValid(string $uuid): bool;
}
