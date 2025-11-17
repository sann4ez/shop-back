<?php

use Illuminate\Support\Facades\Route;

// ADMIN
require __DIR__.'/admin.php';

// STARTED
Route::middleware(['web'])->get('started', function (\Illuminate\Http\Request $request) {
    return \App\Providers\RouteServiceProvider::startedRoute($request);
})->name('started');

Route::get('/', function () {
    return redirect('/admin/users');
});

require __DIR__.'/web-auth.php';

Route::group([
    'middleware' => [\App\Http\Middleware\SetClientDomain::class],
], function () {
    // PAYMENT INFO PAGES
    Route::get('/p/{payment:number}', [\App\Http\Client\Controllers\PaymentController::class, 'redirectToPay'])->name('payment.redirect');
    Route::get('payment/order/{order}', [\App\Http\Client\Controllers\PaymentController::class, 'order'])->name('payment.order');
    Route::any('payment/{info}', [\App\Http\Client\Controllers\PaymentController::class, 'info'])->where('info', '^(progress|cancel)$')->name('payment.info');
});
