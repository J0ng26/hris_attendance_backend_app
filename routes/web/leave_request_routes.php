<?php

use App\Http\Controllers\LeaveRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('/requests')->group(function () {
    Route::post('/submit', [LeaveRequestController::class, 'submit']);
    Route::get('/my-requests', [LeaveRequestController::class, 'myRequests']);
});
