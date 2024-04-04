<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionCreator;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionCreator;

final class XenforoSubscriptionCreatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            SubscriptionCreator::class,
            XenforoSubscriptionCreator::class,
        );
    }
}
