<?php

use App\Http\Middleware\ApiRouteLockMiddleware;
use App\Console\MakeModule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\CheckPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        MakeModule::class,
    ])
    ->withMiddleware(function ($middleware) {
        $middleware->statefulApi();
        $middleware->alias([
            'permission' => CheckPermissionMiddleware::class,
            'app_locked' => ApiRouteLockMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
