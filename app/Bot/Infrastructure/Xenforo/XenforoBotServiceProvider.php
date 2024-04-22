<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Bot\Domain\BotActivator;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;
use olml89\XenforoBotsBackend\Bot\Domain\BotDeactivator;
use olml89\XenforoBotsBackend\Bot\Domain\BotSubscriber;

final class XenforoBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            BotCreator::class,
            XenforoBotCreator::class
        );
        $this->app->bind(
            BotSubscriber::class,
            XenforoBotSubscriber::class
        );
        $this->app->bind(
            BotActivator::class,
            XenforoBotActivator::class
        );
        $this->app->bind(
            BotDeactivator::class,
            XenforoBotDeactivator::class
        );
    }

    public function boot(): void
    {
        $this->app->singleton(
            abstract: XenforoBotSubscriber::class,
            concrete: function(Application $app): XenforoBotSubscriber {
                /** @var XenforoBotSubscriberFactory $xenforoBotSubscriberFactory */
                $xenforoBotSubscriberFactory = $app[XenforoBotSubscriberFactory::class];

                return $xenforoBotSubscriberFactory->create();
            }
        );
    }

    public function provides(): array
    {
        return [
            BotCreator::class,
            BotSubscriber::class,
            BotActivator::class,
        ];
    }
}
