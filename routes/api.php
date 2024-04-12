<?php declare(strict_types=1);

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', function (): Response {
    return response([
        'time' => round((microtime(true) - LARAVEL_START) * 1000),
    ]);
});
