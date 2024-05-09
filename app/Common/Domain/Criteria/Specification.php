<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\Criteria;

interface Specification
{
    public function criteria(): Criteria;
}
