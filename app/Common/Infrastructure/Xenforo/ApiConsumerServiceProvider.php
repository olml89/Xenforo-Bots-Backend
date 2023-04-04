<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo;

use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class ApiConsumerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $config = $this->app['config']->get('xenforo');
        $apiUrl = $config['api_url'];
        $apiKey = $config['api_key'];

        $this->app->singleton(Client::class, function() use ($apiKey): Client {
            return new Client([
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'XF-Api-Key' => $apiKey,
                ],
                'http_errors' => false,
            ]);
        });

        $this->app->singleton(ApiConsumer::class, function(Application $app) use ($apiUrl, $apiKey): ApiConsumer {
            return new ApiConsumer(
                apiUrl: $apiUrl,
                apiKey: $apiKey,
                httpClient: $app->get(Client::class),
            );
        });
    }
}
