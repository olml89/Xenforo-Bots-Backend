<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Illuminate\Foundation\Application;

abstract class InjectableType extends Type
{
    abstract public function inject(Application $app): void;
}
