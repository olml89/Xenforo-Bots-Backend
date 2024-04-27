<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid;

interface UuidGenerator
{
    public function generate(): Uuid;
}
