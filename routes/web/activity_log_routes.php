<?php

use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/activity-logs', [ActivityLogController::class, 'all'])->middleware('permission:super.admin.only');