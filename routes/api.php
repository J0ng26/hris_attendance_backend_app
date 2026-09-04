<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Route::get('/server-time', function () {
    $phTime = Carbon::now('Asia/Manila');

    try {
        // Set MySQL session timezone to Philippine time, then query
        DB::statement("SET time_zone = '+08:00'");
        $dbTime = DB::select("SELECT NOW() as db_time")[0]->db_time;
    } catch (\Throwable $e) {
        $dbTime = $phTime->toDateTimeString();
    }

    return response()->json([
        'database_time' => $dbTime,
        'server_time' => $phTime->format('Y-m-d H:i:s'),
        'timezone' => 'Asia/Manila',
        'timestamp_ms' => (int) (microtime(true) * 1000),
    ]);
});

Route::post('/validate-activation-token', [\App\Http\Controllers\ActivationController::class, 'validateToken']);
Route::post('/activate-account', [\App\Http\Controllers\ActivationController::class, 'activateAccount']);
Route::post('/generate-activation-invite', [\App\Http\Controllers\ActivationController::class, 'generateInvite']);

