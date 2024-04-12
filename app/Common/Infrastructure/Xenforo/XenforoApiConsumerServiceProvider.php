<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use Illuminate\Support\ServiceProvider;

final class XenforoApiConsumerServiceProvider extends ServiceProvider
{
    public function boot(XenforoApiConsumerFactory $xenforoApiConsumerFactory): void
    {
        $this->app->singleton(
            abstract: XenforoApiConsumer::class,
            concrete: fn (): XenforoApiConsumer => $xenforoApiConsumerFactory->create()
        );
    }
}
