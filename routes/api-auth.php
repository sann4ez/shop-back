<?php

// REGISTER, LOGIN, RESET
Route::post('register', [\App\Http\Auth\Api\Controllers\RegisterController::class, 'register']);
Route::post('login', [\App\Http\Auth\Api\Controllers\LoginController::class, 'login']);
Route::post('logout', [\App\Http\Auth\Api\Controllers\LoginController::class, 'logout']);
