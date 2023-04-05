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
        $config = $this->app['config']->get('xenforo');
        $api_url = $config['api_url'];
        $api_key = $config['api_key'];

        $this->app->singleton(Client::class, function() use ($api_key): Client {
            return new Client([
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'XF-Api-Key' => $api_key,
                ],
                'http_errors' => false,
            ]);
        });

        $this->app->singleton(ApiConsumer::class, function(Application $app) use ($api_url, $api_key): ApiConsumer {
            return new ApiConsumer(
                apiUrl: Url::create(
                    $api_url,
                    $app->get(UrlValidator::class),
                ),
                apiKey: $api_key,
                httpClient: $app->get(Client::class),
            );
        });
    }
}
