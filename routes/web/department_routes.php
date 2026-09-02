<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/department')->group(function () {
    Route::get('/all', [DepartmentController::class, 'all'])->middleware('permission:department.read|department.reference');
    Route::post('/create', [DepartmentController::class, 'add'])->middleware('permission:department.create');
    Route::post('/update', [DepartmentController::class, 'edit'])->middleware('permission:department.update');
    Route::post('/delete', [DepartmentController::class, 'delete'])->middleware('permission:department.delete');
});
