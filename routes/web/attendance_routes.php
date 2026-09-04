<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('/attendance')->group(function () {
    Route::post('/record', [AttendanceController::class, 'record']);
    Route::get('/my-records', [AttendanceController::class, 'myRecords']);
    Route::get('/today', [AttendanceController::class, 'today']);
    Route::get('/status', [AttendanceController::class, 'status']);
});
