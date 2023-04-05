<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\BotSubscriber;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBots\Bot\Domain\BotSubscriber;

final class XenforoBotSubscriberServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            BotSubscriber::class,
            XenforoBotSubscriber::class,
        );
    }
}
