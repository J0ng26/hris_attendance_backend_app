<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('/permission')->group(function () {
    Route::get('/index', [PermissionController::class, 'index']);
    Route::get('/all', [PermissionController::class, 'all']);
    Route::get('/special-user-access', [PermissionController::class, 'getAllUsersAndPermissions'])->middleware('permission:permission.view');
    Route::post('/create', [PermissionController::class, 'create'])->middleware('permission:permission.create');
    Route::post('/create-key', [PermissionController::class, 'createKey'])->middleware('permission:permission.create');
    Route::post('/manage', [PermissionController::class, 'manage'])->middleware('permission:permission.create');
    Route::post('/manage-user-access', [PermissionController::class, 'manageSpecialUserAccess'])->middleware('permission:permission.create');
    Route::post('/update', [PermissionController::class, 'update'])->middleware('permission:permission.update');
    Route::post('/update-key', [PermissionController::class, 'updateKey'])->middleware('permission:permission.update');
    Route::post('/delete', [PermissionController::class, 'delete'])->middleware('permission:permission.delete');
    Route::post('/delete-key', [PermissionController::class, 'deleteKey'])->middleware('permission:permission.delete');
});
