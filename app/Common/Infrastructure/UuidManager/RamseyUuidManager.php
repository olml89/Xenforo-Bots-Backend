<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\UuidManager;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidManager as UuidManagerContract;
use Ramsey\Uuid\UuidFactory;

final class RamseyUuidManager implements UuidManagerContract
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
    ) {}

    public function random(): Uuid
    {
        return new Uuid($this->uuidFactory->uuid4()->toString(), $this);
    }

    public function isValid(string $uuid): bool
    {
        return $this->uuidFactory->getValidator()->validate($uuid);
    }
}
