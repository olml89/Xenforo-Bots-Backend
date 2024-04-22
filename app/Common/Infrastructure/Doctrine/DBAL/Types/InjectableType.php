<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types;

use Illuminate\Foundation\Application;

interface InjectableType
{
    public function inject(Application $app): void;
}
