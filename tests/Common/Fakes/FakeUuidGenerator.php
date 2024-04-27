<?php declare(strict_types=1);

namespace Tests\Common\Fakes;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidGenerator;

final readonly class FakeUuidGenerator implements UuidGenerator
{
    public function __construct(
        private Uuid $uuid,
    ) {}

    public function generate(): Uuid
    {
        return $this->uuid;
    }
}
