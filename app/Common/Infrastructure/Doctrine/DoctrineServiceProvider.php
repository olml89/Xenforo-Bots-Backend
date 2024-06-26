<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Connection;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Driver\PersistentMySQLDriver;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\CustomType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\InjectableType;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Throwable;

final class DoctrineServiceProvider extends ServiceProvider
{
    private readonly Config $config;

    public function __construct(Application $app)
    {
        $this->config = $app[Config::class];

        parent::__construct($app);
    }

    public function register(): void
    {
        $this->registerEntityManager();
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

    /**
     * @throws Throwable
     */
    public function boot(): void
    {
        $this->bootCustomTypes();

        $entityManager = $this->bootEntityManager();

        $this->bootListeners($entityManager);
        $this->bootSubscribers($entityManager);

        if ($this->app->runningInConsole()) {
            $this->bootMigrations($entityManager);
        }
    }

    /**
     * This is booted instead of registered, since our defined CustomTypes can contain dependencies that have to be
     * previously instantiated by the application
     *
     * @throws Throwable
     */
    private function bootCustomTypes(): void
    {
        $customTypes = $this->config->get('doctrine.custom_types');

        /** @var class-string<CustomType> $typeClass */
        foreach ($customTypes as $typeClass) {
            $typeName = $typeClass::getTypeName();

            if (Type::hasType($typeName)) {
                continue;
            }

            $type = new $typeClass;

            if ($type instanceof InjectableType) {
                $type->inject($this->app);
            }

            Type::getTypeRegistry()->register($typeName, $type);
        }
    }

    private function bootEntityManager(): EntityManagerInterface
    {
        $this->app->singleton(EntityManagerInterface::class, function(Application $app): EntityManager {
            $config = ORMSetup::createXMLMetadataConfiguration(
                paths: $this->config->get('doctrine.mappings'),
                isDevMode: $app->hasDebugModeEnabled(),
                proxyDir: $this->config->get('doctrine.proxies.path'),
                cache: new ArrayAdapter(),
            );

            /**
             * https://stackoverflow.com/questions/43491683/how-to-inject-existing-pdo-object-to-doctrine-entity-manager
             *
             * You can inject an existing PDO connection into Doctrine, but not like explained here.
             * You have to implement a Driver that uses the already existing connection.
             *
             * @var Connection $laravelConnection
             */
            $laravelConnection = $app[Connection::class];

            $connection = DriverManager::getConnection(
                params: [
                    'driverClass' => PersistentMySQLDriver::class,
                    'pdo' => $laravelConnection->getPdo(),
                    'charset' => $laravelConnection->getConfig('charset'),
                    'collation' => $laravelConnection->getConfig('collation'),
                ],
                config: $config,
            );

            return new EntityManager($connection, $config);
        });

        return $this->app[EntityManagerInterface::class];
    }

    public function bootListeners(EntityManagerInterface $entityManager): void
    {
        foreach ($this->config->get('doctrine.events.listeners') as $event => $listeners) {
            foreach ($listeners as $listener) {
                $entityManager->getEventManager()->addEventListener($event, $listener);
            }
        }
    }

    public function bootSubscribers(EntityManagerInterface $entityManager): void
    {
        foreach ($this->config->get('doctrine.events.subscribers') as $subscriber) {
            $entityManager->getEventManager()->addEventSubscriber($subscriber);
        }
    }

    private function bootMigrations(EntityManagerInterface $entityManager): void
    {
        foreach ($this->config->get('doctrine.migrations.default.migrations_paths') as $migrationsDirectory) {
            if (!is_dir($migrationsDirectory)) {
                mkdir($migrationsDirectory);
            }
        }

        $this->app->singleton(DependencyFactory::class, function() use ($entityManager): DependencyFactory {
            return DependencyFactory::fromEntityManager(
                configurationLoader: new ConfigurationArray(
                    $this->config->get('doctrine.migrations.default')
                ),
                emLoader: new ExistingEntityManager($entityManager),
            );
        });
    }
}
