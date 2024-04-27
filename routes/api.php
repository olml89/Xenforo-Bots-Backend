<?php declare(strict_types=1);

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use olml89\XenforoBotsBackend\Content\Infrastructure\Http\PostPublicInteractionController;

Route::get('/', function(): Response {
    return response([
        'time' => round((microtime(true) - LARAVEL_START) * 1000),
    ]);
});

Route::group(['prefix' => 'bots'], function(): void {
    Route::post('{botId}/interactions/public', PostPublicInteractionController::class);
});
