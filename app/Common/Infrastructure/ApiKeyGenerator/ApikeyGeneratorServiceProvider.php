<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\ApiKeyGenerator;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKeyGenerator;

final class ApikeyGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ApiKeyGenerator::class,
            XenforoApiKeyGenerator::class
        );
    }
}
