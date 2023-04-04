<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Domain\ValueObjects;

use Stringable;

abstract class StringValueObject implements Stringable
{
    public function __construct(
        protected readonly string $value,
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }
}
