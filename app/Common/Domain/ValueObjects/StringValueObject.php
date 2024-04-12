<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Domain\ValueObjects;

use Stringable;

interface StringValueObject extends Stringable
{
    public function value(): string;
}
