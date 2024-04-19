<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Infrastructure\Xenforo;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionCreator;

final class XenforoSubscriptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            SubscriptionCreator::class,
            XenforoBotSubscriptionCreator::class,
        );
    }

    public function boot(): void
    {
        $this->app->singleton(
            abstract: XenforoBotSubscriptionCreator::class,
            concrete: function(Application $app): XenforoBotSubscriptionCreator {
                /** @var XenforoBotSubscriptionCreatorFactory $xenforoBotSubscriptionCreatorFactory */
                $xenforoBotSubscriptionCreatorFactory = $app[XenforoBotSubscriptionCreatorFactory::class];

                return $xenforoBotSubscriptionCreatorFactory->create();
            }
        );
    }

    public function provides(): array
    {
        return [
            SubscriptionCreator::class,
        ];
    }
}
