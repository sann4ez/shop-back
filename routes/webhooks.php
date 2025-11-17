<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'webhooks.', /*'middleware' => \App\Http\Middleware\LogRoutes::class*/], function () {
    // https://site.com/webhooks/payment/notify/{monobank}
    Route::any('payment/notify/{gateway}', [\App\Http\Webhooks\Controllers\PaymentController::class, 'notify'])->name('payment.notify');
});
