<?php declare(strict_types=1);

use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Bot\Infrastructure\Persistence\DoctrineBotRepository;
use olml89\XenforoBots\Common\Infrastructure\Doctrine\Migrations\Commands\DiffCommand;
use olml89\XenforoBots\Common\Infrastructure\Doctrine\Migrations\Commands\ExecuteCommand;
use olml89\XenforoBots\Common\Infrastructure\Doctrine\Migrations\Commands\MigrateCommand;
use olml89\XenforoBots\Common\Infrastructure\Doctrine\Migrations\Commands\ResetCommand;
use olml89\XenforoBots\Common\Infrastructure\Doctrine\Types\AutoIdType;
use olml89\XenforoBots\Common\Infrastructure\Doctrine\Types\PasswordType;

return [

    'connection' => [
        'driver' => env('DOCTRINE_DRIVER', 'pdo_mysql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'dbname' => env('DB_DATABASE', 'laravel'),
        'user' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],

    'mappings' => [
        app_path('Bot/Infrastructure/Persistence'),
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
        AutoIdType::class,
        PasswordType::class,
    ],

    'repositories' => [
        BotRepository::class => DoctrineBotRepository::class,
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
            ResetCommand::class,
        ],

    ],

];

