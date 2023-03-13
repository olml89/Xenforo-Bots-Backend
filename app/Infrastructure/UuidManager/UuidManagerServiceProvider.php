<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\UuidManager;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\UuidFactory;

final class UuidManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(UuidManager::class, function(Application $app): UuidManager {
            return new UuidManager($app->get(UuidFactory::class));
        });
    }
}
