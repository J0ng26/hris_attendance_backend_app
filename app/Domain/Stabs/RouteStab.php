<?php

namespace App\Domain\Stabs;

class RouteStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
        <?php

        use {{ controller }};
        use Illuminate\Support\Facades\Route;

        Route::prefix('/{{ prefix }}')->group(function () {
            Route::get('/index', [{{ controllerClass }}::class, 'index']);
            Route::get('/show', [{{ controllerClass }}::class, 'show']);
            Route::post('/create', [{{ controllerClass }}::class, 'create'])->middleware('permission:{{ prefix }}.create');
            Route::post('/update', [{{ controllerClass }}::class, 'update'])->middleware('permission:{{ prefix }}.update');
            Route::post('/delete', [{{ controllerClass }}::class, 'delete'])->middleware('permission:{{ prefix }}.delete');
        });
        STUB;
    }
}
