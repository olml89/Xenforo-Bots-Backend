<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

final class DoctrineServiceProvider extends ServiceProvider
{
    /**
     * @throws Exception
     */
    public function register(): void
    {
        $this->registerEntityManager();
        $this->registerCustomTypes();
        $this->registerRepositories();
    }

    private function registerEntityManager(): void
    {
        $this->app->bind(
            EntityManagerInterface::class,
            EntityManager::class,
        );
    }

    /**
     * @throws Exception
     */
    private function registerCustomTypes(): void
    {
        $customTypes = $this->app['config']->get('doctrine.customTypes');

        /** @var class-string<Type> $typeClass */
        foreach ($customTypes as $typeClass) {
            $type = new $typeClass();
            Type::getTypeRegistry()->register($type->getName(), $type);
        }
    }

    private function registerRepositories(): void
    {
        $repositories = $this->app['config']->get('doctrine.repositories');

        foreach ($repositories as $repository => $implementation) {
            $this->app->bind($repository, $implementation);
        }
    }

    public function boot(): void
    {
        $this->bootEntityManager();
    }

    private function bootEntityManager(): void
    {
        $this->app->singleton(EntityManagerInterface::class, function(Application $app): EntityManager {

            $configValues = $app->get('config')->get('doctrine');

            $config = ORMSetup::createXMLMetadataConfiguration(
                paths: [__DIR__.'/Mappings'],
                isDevMode: $app->hasDebugModeEnabled(),
                proxyDir: $configValues['proxyDir'],
                cache: new ArrayAdapter(),
            );

            $connection = DriverManager::getConnection(
                params: $configValues['connection'],
                config: $config,
            );

            return new EntityManager($connection, $config);
        });
    }
}
