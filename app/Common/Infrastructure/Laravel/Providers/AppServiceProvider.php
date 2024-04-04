<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Providers;

use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Url::class, function(Application $app): Url {

            /** @var Config $config */
            $config = $app->get(Config::class);

            /** @var UrlValidator $urlValidator */
            $urlValidator = $app->get(UrlValidator::class);

            return Url::create(
                $config->get('app')['url'],
                $urlValidator,
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
