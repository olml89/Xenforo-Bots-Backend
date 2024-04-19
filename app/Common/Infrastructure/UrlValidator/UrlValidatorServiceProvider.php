<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\UrlValidator;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\UrlValidator;

final class UrlValidatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UrlValidator::class,
            LaravelUrlValidator::class
        );
    }
}
