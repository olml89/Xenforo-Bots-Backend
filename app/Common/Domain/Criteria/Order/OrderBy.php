<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\Order;

use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\StringValueObject;

final readonly class OrderBy implements StringValueObject
{
    public function __construct(
        private string $orderBy,
    ) {}

    public function value(): string
    {
        return $this->orderBy;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
