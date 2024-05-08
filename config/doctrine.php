<?php declare(strict_types=1);

use olml89\XenforoBotsBackend\Behaviour\Domain\BehaviourRepository;
use olml89\XenforoBotsBackend\Behaviour\Infrastructure\Doctrine\DoctrineBehaviourRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Doctrine\DoctrineBotRepository;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\ApiKeyType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\AutoIdType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\BehaviourNameType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\BehaviourPatternType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\UnixTimestampType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\UrlType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\UsernameType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\DBAL\Types\UuidType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Migrations\Commands\DiffCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Migrations\Commands\ExecuteCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Migrations\Commands\MigrateCommand;
use olml89\XenforoBotsBackend\Content\Domain\ContentRepository;
use olml89\XenforoBotsBackend\Content\Infrastructure\Doctrine\DoctrineContentRepository;

return [

    'mappings' => [
        app_path('Behaviour/Infrastructure/Doctrine'),
        app_path('Bot/Infrastructure/Doctrine'),
        app_path('Content/Infrastructure/Doctrine'),
    ],

    'proxies' => [
        'namespace' => false,
        'path' => storage_path('proxies'),
        'auto_generate' => config('app.debug', false)
    ],

    'events' => [
        'listeners' => [],
        'subscribers' => [],
    ],

    'filters' => [],

    'custom_types' => [
        UuidType::class,
        AutoIdType::class,
        UrlType::class,
        UnixTimestampType::class,
        ApiKeyType::class,
        UsernameType::class,
        BehaviourNameType::class,
        BehaviourPatternType::class,
    ],

    'repositories' => [
        BehaviourRepository::class => DoctrineBehaviourRepository::class,
        BotRepository::class => DoctrineBotRepository::class,
        ContentRepository::class => DoctrineContentRepository::class,
    ],

    'migrations' => [

        'schema' => [
            'filter' => '/^(?!password_resets|failed_jobs).*$/',
        ],

        'default' => [

            'table_storage' => [
                'table_name' => 'doctrine_migrations',
                'version_column_name' => 'version',
                'version_column_length' => 191,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],

            'migrations_paths' => [
                'Database\\Migrations' => database_path('doctrine-migrations'),
            ],

            'all_or_nothing' => true,
            'transactional' => true,
            'check_database_platform' => true,
            'organize_migrations' => 'none',

        ],

        'commands' => [
            DiffCommand::class,
            ExecuteCommand::class,
            MigrateCommand::class,
        ],

    ],

];

