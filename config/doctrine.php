<?php declare(strict_types=1);

use olml89\XenforoBots\Domain\Bot\BotRepository;
use olml89\XenforoBots\Infrastructure\Doctrine\Repositories\DoctrineBotRepository;
use olml89\XenforoBots\Infrastructure\Doctrine\Types\AutoIdType;
use olml89\XenforoBots\Infrastructure\Doctrine\Types\PasswordType;

return [

    'connection' => [
        'driver' => 'pdo_mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'dbname' => env('DB_DATABASE', 'laravel'),
        'user' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
    ],

    'proxyDir' => storage_path('proxies'),

    'customTypes' => [
        AutoIdType::class,
        PasswordType::class,
    ],

    'repositories' => [
        BotRepository::class => DoctrineBotRepository::class,
    ],

];

