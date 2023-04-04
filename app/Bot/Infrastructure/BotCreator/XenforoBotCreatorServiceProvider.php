<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\BotCreator;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBots\Bot\Domain\BotCreator;

final class XenforoBotCreatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            BotCreator::class,
            XenforoBotCreator::class,
        );
    }
}
