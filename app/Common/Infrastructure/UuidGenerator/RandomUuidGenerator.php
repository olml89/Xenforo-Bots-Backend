<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\UuidGenerator;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use Ramsey\Uuid\Uuid as UuidFactory;

final class RandomUuidGenerator implements UuidGenerator
{
    public function generate(): Uuid
    {
        return new Uuid(UuidFactory::uuid4());
    }
}
