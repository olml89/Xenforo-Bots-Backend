<?php declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\ActivateBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\DeactivateBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\IndexBotsCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\RetrieveBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\SubscribeBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\UnsubscribeBotCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Console\CreateDatabaseCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Console\GenerateApiKeyCommand;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
    )
    ->withCommands([
        GenerateApiKeyCommand::class,
        CreateDatabaseCommand::class,
        IndexBotsCommand::class,
        SubscribeBotCommand::class,
        RetrieveBotCommand::class,
        UnsubscribeBotCommand::class,
        ActivateBotCommand::class,
        DeactivateBotCommand::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
