<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo;

use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBots\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBots\Common\Domain\ValueObjects\Url\UrlValidator;

final class ApiConsumerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $api_url = $this->app['config']->get('xenforo')['api_url'];
        $api_key = $this->app['config']->get('xenforo')['api_key'];
        $app_url = $this->app['config']->get('app')['url'];

        $this->app->singleton(Client::class, function() use ($api_key): Client {
            return new Client([
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'XF-Api-Key' => $api_key,
                ],
                'http_errors' => false,
            ]);
        });

        $this->app->singleton(
            ApiConsumer::class,
            function(Application $app) use ($api_url, $api_key, $app_url): ApiConsumer {
                /** @var UrlValidator $urlValidator */
                $urlValidator = $app->get(UrlValidator::class);

                return new ApiConsumer(
                    apiUrl: Url::create($api_url, $urlValidator),
                    apiKey: $api_key,
                    httpClient: $app->get(Client::class),
                    appUrl: Url::create($app_url, $urlValidator),
                );
            }
        );
    }
}
