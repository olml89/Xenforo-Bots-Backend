<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Domain\ValueObjects;

abstract class IntValueObject
{
    public function __construct(
        protected readonly int $value,
    ) {}

    public function toInt(): int
    {
        return $this->value;
    }
}
