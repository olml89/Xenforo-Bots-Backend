<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo;

use Illuminate\Support\ServiceProvider;

final class XenforoApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(XenforoApi::class, function(): XenforoApi {
            /** @var XenforoApiFactory $xenforoApiFactory */
            $xenforoApiFactory = $this->app->get(XenforoApiFactory::class);

            return $xenforoApiFactory->create();
        });
    }
}
