<?php declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\ActivateBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\CancelBotSubscriptionCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\SubscribeBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\ShowBotSubscriptionCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\SyncBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\UpdateBotSubscriptionCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Console\CreateDatabaseCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Console\GenerateApiKeyCommand;
use olml89\XenforoBotsBackend\Reply\Infrastructure\Console\PublishReplyCommand;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
    )
    ->withCommands([
        GenerateApiKeyCommand::class,
        CreateDatabaseCommand::class,
        SubscribeBotCommand::class,
        ActivateBotCommand::class,
        SyncBotCommand::class,
        UpdateBotSubscriptionCommand::class,
        CancelBotSubscriptionCommand::class,
        ShowBotSubscriptionCommand::class,
        PublishReplyCommand::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
