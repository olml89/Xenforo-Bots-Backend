<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Infrastructure\SubscriptionCreator;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBots\Subscription\Domain\SubscriptionCreator;

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
