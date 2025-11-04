<?php

// REGISTER, LOGIN, RESET
//Route::post('register', [\App\Http\Auth\Api\Controllers\RegisterController::class, 'register']);
//Route::post('login', [\App\Http\Auth\Api\Controllers\LoginController::class, 'login']);
//Route::post('logout', [\App\Http\Auth\Api\Controllers\LoginController::class, 'logout']);
//Route::post('password/forgot', [\App\Http\Auth\Api\Controllers\ResetPasswordController::class, 'forgot']);
//Route::post('password/reset', [\App\Http\Auth\Api\Controllers\ResetPasswordController::class, 'reset']);
//Route::match(['get', 'post'], 'verify', [\App\Http\Auth\Api\Controllers\VerificationController::class, 'verify'])->name('api.verify');
//Route::post('verify/resend', [\App\Http\Auth\Api\Controllers\VerificationController::class, 'resend']);
//
//// SOCIALITE
//Route::post('socialite/{provider}', [\App\Http\Auth\Api\Controllers\SocialiteController::class, 'socialLogin'])->name('socialite.oauth');
//Route::get('socialite/{provider}', [\App\Http\Auth\Api\Controllers\SocialiteController::class, 'redirectToProvider'])->name('socialite.oauth');
//Route::any('socialite/{provider}/callback', [\App\Http\Auth\Api\Controllers\SocialiteController::class, 'handleProviderCallback'])->name('socialite.oauth.callback');
//
//Route::group(['prefix' => 'auth/otp', 'middleware' => ['throttle:auth-attempts']], function () {
//    Route::post('send', [\App\Http\Auth\Api\Controllers\OtpController::class, 'send']);
//    Route::post('check', [\App\Http\Auth\Api\Controllers\OtpController::class, 'check']);
//    Route::post('resend', [\App\Http\Auth\Api\Controllers\OtpController::class, 'resend']);
//});
