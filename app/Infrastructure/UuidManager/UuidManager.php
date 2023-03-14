<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\UuidManager;

use olml89\XenforoBots\Domain\ValueObjects\Uuid\UuidManager as UuidManagerContract;
use Ramsey\Uuid\UuidFactory;

final class UuidManager implements UuidManagerContract
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
    ) {}

    public function random(): string
    {
        return $this->uuidFactory->uuid4()->toString();
    }

    public function isValid(string $uuid): bool
    {
        return $this->uuidFactory->getValidator()->validate($uuid);
    }
}