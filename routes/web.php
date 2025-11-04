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
