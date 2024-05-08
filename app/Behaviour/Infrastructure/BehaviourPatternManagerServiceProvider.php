<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Behaviour\Infrastructure;

use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPattern;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPatternHandler;
use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourPatternManager;
use olml89\XenforoBotsBackend\Behaviour\Domain\InvalidBehaviourPatternHandlerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BehaviourPatternManagerServiceProvider extends ServiceProvider
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws InvalidBehaviourPatternHandlerException
     */
    public function boot(Repository $config): void
    {
        $behaviourPatterns = array_map(
            fn (string $behaviourPatternHandler): BehaviourPattern => $this->app->get(
                BehaviourPatternHandler::create($behaviourPatternHandler)->value()
            ),
            $config->get('behaviours')
        );

        $this->app->singleton(
            abstract: BehaviourPatternManager::class,
            concrete: fn (): BehaviourPatternManager => new BehaviourPatternManager(...$behaviourPatterns)
        );
    }

    public function provides(): array
    {
        return [
            BehaviourPatternManager::class,
        ];
    }
}
