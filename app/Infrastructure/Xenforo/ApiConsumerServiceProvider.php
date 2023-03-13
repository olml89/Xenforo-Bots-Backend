<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Xenforo;

use GuzzleHttp\Client;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class ApiConsumerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @var Repository $config */
        $config = $this->app->get('config');

        $apiUrl = $config->get('xenforo.api_url');
        $apiKey = $config->get('xenforo.api_key');

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
