<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria\Order;

enum OrderType: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
