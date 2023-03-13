<?php

namespace olml89\XenforoBots\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \olml89\XenforoBots\Domain\Bot\BotCreator::class,
            \olml89\XenforoBots\Infrastructure\Xenforo\BotCreator\BotCreator::class,
        );
        $this->app->bind(
            \olml89\XenforoBots\Domain\ValueObjects\Password\Hasher::class,
            \olml89\XenforoBots\Infrastructure\Hasher\Hasher::class,
        );
        $this->app->bind(
            \olml89\XenforoBots\Domain\ValueObjects\Uuid\UuidManager::class,
            \olml89\XenforoBots\Infrastructure\UuidManager\UuidManager::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
