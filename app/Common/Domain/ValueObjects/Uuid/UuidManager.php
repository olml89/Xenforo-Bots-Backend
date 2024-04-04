<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid;

interface UuidManager
{
    public function random(): Uuid;
    public function isValid(string $uuid): bool;
}
