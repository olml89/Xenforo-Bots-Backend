<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

abstract readonly class Filter implements Expression
{
    public function __construct(
        public Field $field,
        public Operator $operator,
        public mixed $value,
    ) {}

    public function __toString(): string
    {
        return sprintf(
            '%s %s %s',
            $this->field,
            $this->operator->value,
            $this->value,
        );
    }
}
