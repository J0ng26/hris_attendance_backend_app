<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AppSettingsController;

Route::post('/authenticate', [AuthenticationController::class, 'authenticate'])->prefix('/api');
Route::prefix('api')->group(function () {
    Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword'])->middleware('throttle:5,1');
    Route::post('/otp', [AuthenticationController::class, 'checkOtp']);
    Route::post('/change-password', [AuthenticationController::class, 'changePassword']);
});

Route::middleware(['auth:sanctum', 'app_locked'])->prefix('/api')->group(function () {
    Route::get('/user', [AuthenticationController::class, 'user']);

    Route::prefix('/app-setting')->group(function () {
        Route::post('/lock-api', [AppSettingsController::class, 'lockApi']);
        Route::post('/unlock-api', [AppSettingsController::class, 'unlockApi']);
    });

    Route::prefix('/user-control')->group(function () {
        Route::post('/reset-password', [AuthenticationController::class, 'resetPassword']);
        Route::post('/logout', [AuthenticationController::class, 'logout']);
    });
});
