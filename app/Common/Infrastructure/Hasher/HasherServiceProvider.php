<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Hasher;

use Illuminate\Foundation\Application;
use Illuminate\Hashing\HashManager;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Password\Hasher as HasherContract;

final class HasherServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(HasherContract::class, function(Application $app): LaravelHasher {

            /** @var HashManager $hashManager */
            $hashManager = $app->get(HashManager::class);

            return new LaravelHasher($hashManager->driver());
        });
    }
}
