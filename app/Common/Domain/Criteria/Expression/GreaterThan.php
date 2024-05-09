<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

final readonly class GreaterThan extends Filter
{
    public function __construct(Field $field, mixed $value)
    {
        parent::__construct(
            field: $field,
            operator: Operator::GT,
            value: $value
        );
    }
}
