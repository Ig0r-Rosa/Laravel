<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rota intermédiaria para os caminhos
Route::middleware('web')->group(function () 
{
    Route::get('/auth', [AuthController::class, 'showForm'])->name('auth.form');
    Route::post('/auth/login', [AuthController::class, 'authenticate'])->name('auth.login');
    Route::get('/auth/status', [AuthController::class, 'status'])->name('auth.status');
    Route::post('/auth/refresh', [AuthController::class, 'refreshToken'])->name('auth.refresh');
    Route::get('/notices', [AuthController::class, 'notices'])->name('auth.notices');

    // Rota para redirecionar para autenticação caso esteja em "/"
    Route::get('/', function () 
        {
            return redirect()->route('auth.form');
        }
    )->name('home');
});