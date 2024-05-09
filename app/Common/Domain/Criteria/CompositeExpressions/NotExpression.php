<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

final readonly class NotExpression extends CompositeExpression
{
    public function __construct(
        public Expression $clause,
    ) {
        parent::__construct(Type::NOT);
    }

    public function __toString(): string
    {
        return sprintf(
            '(NOT %s)',
            $this->clause,
        );
    }
}
