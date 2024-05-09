<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\CompositeExpressions;

enum Type: string
{
    case AND = 'AND';
    case OR = 'OR';
    case NOT = 'NOT';
}
