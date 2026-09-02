<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/user-control')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:user.read|user.reference');
    Route::get('/users-non-admin', [UserController::class, 'getUserNonAdmin'])->middleware('permission:user.read|user.reference');
    Route::post('/create', [UserController::class, 'add'])->middleware('permission:user.create');
    Route::post('/update', [UserController::class, 'edit'])->middleware('permission:user.update');
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/delete', [UserController::class, 'delete'])->middleware('permission:user.delete');
});