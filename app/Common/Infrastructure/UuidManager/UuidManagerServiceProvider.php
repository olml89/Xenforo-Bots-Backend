<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\UuidManager;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\UuidManager;
use Ramsey\Uuid\UuidFactory;

final class UuidManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(UuidManager::class, function(Application $app): RamseyUuidManager {
            return new RamseyUuidManager($app->get(UuidFactory::class));
        });
    }
}
