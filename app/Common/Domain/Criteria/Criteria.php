<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Order\Order;
use Stringable;

final readonly class Criteria implements Stringable
{
    public function __construct(
        public Expression $expression,
        public ?Order $order = null,
        public ?int $offset = null,
        public ?int $limit = null,
    ) {}

    public function __toString(): string
    {
        $partialStrings = array_filter([
            (string)$this->expression,
            (string)$this->order,
            $this->offset,
            $this->limit,
        ]);

        return implode(', ', $partialStrings);
    }
}
