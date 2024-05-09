<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class Field implements StringValueObject
{
    public function __construct(
        private string $field,
    ) {}

    public function value(): string
    {
        return $this->field;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
