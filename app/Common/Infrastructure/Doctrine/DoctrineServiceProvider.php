<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

final class DoctrineServiceProvider extends ServiceProvider
{
    private readonly Config $config;

    public function __construct(Application $app)
    {
        $this->config = $app[Config::class];

        parent::__construct($app);
    }

    /**
     * @throws Exception
     */
    public function register(): void
    {
        $this->registerEntityManager();
        $this->registerCustomTypes();
        $this->registerRepositories();

        if ($this->app->runningInConsole()) {
            $this->registerMigrationsCommands();
        }
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
        $customTypes = $this->config->get('doctrine.custom_types');

        /** @var class-string<Type> $typeClass */
        foreach ($customTypes as $typeClass) {
            $type = new $typeClass();
            Type::getTypeRegistry()->register($type->getName(), $type);
        }
    }

    private function registerRepositories(): void
    {
        $repositories = $this->config->get('doctrine.repositories');

        foreach ($repositories as $repository => $implementation) {
            $this->app->bind($repository, $implementation);
        }
    }

    private function registerMigrationsCommands(): void
    {
        $migrationCommands = $this->config->get('doctrine.migrations.commands');

        $this->commands($migrationCommands);
    }

    public function boot(): void
    {
        $this->bootEntityManager();

        if ($this->app->runningInConsole()) {
            $this->bootMigrations();
        }
    }

    private function bootEntityManager(): void
    {
        $this->app->singleton(EntityManagerInterface::class, function(Application $app): EntityManager {
            $config = ORMSetup::createXMLMetadataConfiguration(
                paths: $this->config->get('doctrine.mappings'),
                isDevMode: $app->hasDebugModeEnabled(),
                proxyDir: $this->config->get('doctrine.proxies.path'),
                cache: new ArrayAdapter(),
            );

            $connection = DriverManager::getConnection(
                params: $this->config->get('doctrine.connection'),
                config: $config,
            );

            return new EntityManager($connection, $config);
        });
    }

    private function bootMigrations(): void
    {
        $this->app->singleton(DependencyFactory::class, function(Application $app): DependencyFactory {
            return DependencyFactory::fromEntityManager(
                configurationLoader: new ConfigurationArray(
                    $this->config->get('doctrine.migrations.default')
                ),
                emLoader: $app->get(EntityManagerInterface::class),
            );
        });
    }
}
