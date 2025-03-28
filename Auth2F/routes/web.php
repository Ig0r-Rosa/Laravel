<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showConfig'])->name('auth.config');
Route::post('/configure', [AuthController::class, 'configure']);
Route::post('/authenticate', [AuthController::class, 'authenticate']);
Route::post('/send-2fa', [AuthController::class, 'send2FA']);
Route::post('/verify-2fa', [AuthController::class, 'verify2FA']);