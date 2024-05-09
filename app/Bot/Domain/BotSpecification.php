<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Specification;

interface BotSpecification extends Specification
{
    public function isSatisfiedBy(Bot $bot): bool;
}
