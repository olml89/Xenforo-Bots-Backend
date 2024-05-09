<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

final readonly class NotIn extends Filter
{
    public function __construct(Field $field, mixed $value)
    {
        parent::__construct(
            field: $field,
            operator: Operator::NOT_IN,
            value: $value
        );
    }
}
