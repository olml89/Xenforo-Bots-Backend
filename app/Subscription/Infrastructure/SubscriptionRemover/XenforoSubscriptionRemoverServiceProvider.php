<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionRemover;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionRemover;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionRemover\XenforoSubscriptionRemover;

final class XenforoSubscriptionRemoverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            SubscriptionRemover::class,
            XenforoSubscriptionRemover::class,
        );
    }
}
