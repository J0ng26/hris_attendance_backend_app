<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserTypeController;

Route::prefix('/user-type')->group(function () {
    Route::get('/index', [UserTypeController::class, 'index'])->middleware('permission:user-type.read|user-type.reference');
    Route::get('/all', [UserTypeController::class, 'all'])->middleware('permission:user-type.read');
    Route::get('/show/permissions', [UserTypeController::class, 'showPermissions']);
    Route::post('/create', [UserTypeController::class, 'create'])->middleware('permission:user-type.create');
    Route::post('/update', [UserTypeController::class, 'update'])->middleware('permission:user-type.update');
    Route::post('/delete', [UserTypeController::class, 'delete'])->middleware('permission:user-type.delete');
});