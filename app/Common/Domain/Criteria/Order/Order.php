<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\Order;

use Stringable;

final readonly class Order implements Stringable
{
    public function __construct(
        public OrderBy $orderBy,
        public OrderType $orderType,
    ) {}

    public function __toString(): string
    {
        return sprintf(
            '%s %s',
            $this->orderBy,
            $this->orderType->value,
        );
    }
}
