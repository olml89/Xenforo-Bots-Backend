<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Bot\Domain\BotActivator;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;

final class XenforoBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            BotCreator::class,
            XenforoBotCreator::class
        );
        $this->app->bind(
            BotActivator::class,
            XenforoBotActivator::class
        );
    }

    public function provides(): array
    {
        return [
            BotCreator::class,
            BotActivator::class,
        ];
    }
}
