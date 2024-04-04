<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionRetriever;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionRetriever;

final class XenforoSubscriptionRetrieverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            SubscriptionRetriever::class,
            XenforoSubscriptionRetriever::class,
        );
    }
}
