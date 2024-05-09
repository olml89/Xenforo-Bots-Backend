<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Content\Domain;

use olml89\XenforoBotsBackend\Common\Domain\Criteria\Specification;

interface ContentSpecification extends Specification
{
    public function isSatisfiedBy(Content $content): bool;
}
