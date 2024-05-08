<?php declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use olml89\XenforoBotsBackend\Behaviour\Infrastructure\Console\RegisterBehaviourCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\ActivateBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\DeactivateBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\IndexBotsCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\RetrieveBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\SubscribeBotCommand;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Console\UnsubscribeBotCommand;
use olml89\XenforoBotsBackend\Common\Domain\Exceptions\EntityAlreadyExistsException;
use olml89\XenforoBotsBackend\Common\Domain\Exceptions\EntityNotFoundException;
use olml89\XenforoBotsBackend\Common\Domain\Exceptions\EntityValidationException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Console\CreateDatabaseCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Console\GenerateApiKeyCommand;
use olml89\XenforoBotsBackend\Common\Infrastructure\Laravel\Http\Middleware\EnsurePlatformApiKeyIsValid;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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
        RegisterBehaviourCommand::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(EnsurePlatformApiKeyIsValid::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->map(
            from: EntityNotFoundException::class,
            to: fn (EntityNotFoundException $e): NotFoundHttpException => new NotFoundHttpException(
                message: $e->getMessage(),
                previous: $e,
            )
        );
        $exceptions->map(
            from: EntityAlreadyExistsException::class,
            to: fn (EntityAlreadyExistsException $e): ConflictHttpException => new ConflictHttpException(
                message: $e->getMessage(),
                previous: $e,
            )
        );
        $exceptions->map(
            from: EntityValidationException::class,
            to: fn (EntityValidationException $e): UnprocessableEntityHttpException => new UnprocessableEntityHttpException(
                message: $e->getMessage(),
                previous: $e,
            )
        );
    })
    ->create();
