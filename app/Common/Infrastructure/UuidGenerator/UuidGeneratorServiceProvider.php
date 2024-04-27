<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\UuidGenerator;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidGenerator;

final class UuidGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UuidGenerator::class,
            RandomUuidGenerator::class
        );
    }
}
