<?php declare(strict_types=1);

use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types\ApiKeyType;
use olml89\XenforoBotsBackend\Reply\Domain\ReplyRepository;
use olml89\XenforoBotsBackend\Reply\Infrastructure\Persistence\DoctrineReplyRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Persistence\DoctrineBotRepository;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Migrations\Commands\DiffCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Migrations\Commands\ExecuteCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Migrations\Commands\MigrateCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types\UrlType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types\UsernameType;
use olml89\XenforoBotsBackend\Common\Infrastructure\Doctrine\Types\UuidType;

return [

    'connection' => [
        'driver' => env('DOCTRINE_DRIVER', 'pdo_mysql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', 3306),
        'dbname' => env('DB_DATABASE', 'laravel'),
        'user' => env('DB_USER', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],

    'mappings' => [
        app_path('Bot/Infrastructure/Persistence'),
        //app_path('Reply/Infrastructure/Persistence'),
        //app_path('Subscription/Infrastructure/Persistence'),
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
        UuidType::NAME => UuidType::class,
        ApiKeyType::NAME => ApiKeyType::class,
        UsernameType::NAME => UsernameType::class,
        UrlType::NAME => UrlType::class,
    ],

    'repositories' => [
        BotRepository::class => DoctrineBotRepository::class,
        ReplyRepository::class => DoctrineReplyRepository::class,
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

