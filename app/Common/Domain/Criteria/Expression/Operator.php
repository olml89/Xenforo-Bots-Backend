<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\Expression;

enum Operator: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case GT = '>';
    case GTE = '>=';
    case LT = '<';
    case LTE = '<=';
    case IN = 'IN';
    case NOT_IN = 'NOT_IN';
}
