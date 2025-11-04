<?php

use App\Http\Auth\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', \App\Http\Middleware\SetClientDomain::class])->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth', \App\Http\Middleware\SetClientDomain::class])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
