<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Infrastructure\BotAuthenticator;

use Illuminate\Support\ServiceProvider;
use olml89\XenforoBots\Bot\Domain\BotAuthenticator;

final class XenforoBotAuthenticatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            BotAuthenticator::class,
            XenforoBotAuthenticator::class,
        );
    }
}
