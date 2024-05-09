<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

abstract readonly class CompositeExpression implements Expression
{
    public function __construct(
        public Type $type,
    ) {}
}
