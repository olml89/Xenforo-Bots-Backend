<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

final readonly class AndExpression extends CompositeExpression
{
    /**
     * @var Expression[]
     */
    public array $clauses;

    public function __construct(Expression ...$clauses)
    {
        $this->clauses = $clauses;

        parent::__construct(Type::AND);
    }

    public function __toString(): string
    {
        return sprintf(
            '(%s)',
            implode(' AND ', $this->clauses),
        );
    }
}
