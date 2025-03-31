<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('web')->group(function () {
    // Route::get('/', funciton()
    // {
    //     //return middleware('auth');
    // });
    Route::get('/auth', [AuthController::class, 'showForm'])->name('auth.form');
    Route::post('/auth/login', [AuthController::class, 'authenticate'])->name('auth.login');
    Route::get('/auth/status', [AuthController::class, 'status'])->name('auth.status');
    Route::post('/auth/refresh', [AuthController::class, 'refreshToken'])->name('auth.refresh');
});