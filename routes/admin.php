<?php

use Illuminate\Support\Facades\Route;

Route::get('admin/login', [\App\Http\Auth\Controllers\AuthenticatedSessionController::class, 'adminLogin']);
Route::post('admin/login', [\App\Http\Auth\Controllers\AuthenticatedSessionController::class, 'store']);

Route::group([
    'middleware' => ['auth', \App\Http\Middleware\SetAdminDomain::class],
    'prefix' => 'admin',
    'as' => 'admin.',
], function () {
    // HOME
    Route::redirect('/', '/admin/users'); // admin/orders

    // USERS
    Route::resource('users', \App\Http\Admin\Controllers\UserController::class);

    // PAGES
    Route::resource('pages', \App\Http\Admin\Controllers\PageController::class, ['except' => 'show']);
    Route::post('pages/{page}/blocks/attach', [\App\Http\Admin\Controllers\PageController::class, 'blocksAttach'])->name('pages.blocks.attach');
    Route::post('pages/{page}/blocks/detach', [\App\Http\Admin\Controllers\PageController::class, 'blocksDetach'])->name('pages.blocks.detach');
    Route::post('pages/{page}/blocks/order', [\App\Http\Admin\Controllers\PageController::class, 'blocksOrder'])->name('pages.blocks.order');

    // BLOCKS
    Route::resource('blocks', \App\Http\Admin\Controllers\BlockController::class);
    Route::get('blocks/{block}/cloning', [\App\Http\Admin\Controllers\BlockController::class, 'cloning'])->name('blocks.cloning');

    // PRODUCTS
    Route::resource('products', \App\Http\Admin\Controllers\ProductController::class, ['except' => 'show']);
    Route::post('products/{product}/attrs', [\App\Http\Admin\Controllers\ProductController::class, 'attrsSave'])->name('products.attrs.save');
    Route::post('products/sync', [\App\Http\Admin\Controllers\ProductController::class, 'sync'])->name('products.sync');

    Route::get('products/{product}/variations', [\App\Http\Admin\Controllers\VariationController::class, 'create'])->name('products.variations.create');
    Route::post('products/{product}/variations', [\App\Http\Admin\Controllers\VariationController::class, 'store'])->name('products.variations.store');
    Route::get('products/variations/{product_variation}/edit', [\App\Http\Admin\Controllers\VariationController::class, 'edit'])->name('products.variations.edit');
    Route::get('products/variations/generate/{field}', [\App\Http\Admin\Controllers\VariationController::class, 'generateValue'])->name('products.variations.generateValue');
    Route::patch('products/variations/{product_variation}', [\App\Http\Admin\Controllers\VariationController::class, 'update'])->name('products.variations.update');
    Route::delete('products/variations/{product_variation}', [\App\Http\Admin\Controllers\VariationController::class, 'destroy'])->name('products.variations.delete');
    Route::post('products/variations/{product_variation}/default', [\App\Http\Admin\Controllers\VariationController::class, 'default'])->name('products.variations.default');
    Route::post('products/variations/{product_variation}/editable', [\App\Http\Admin\Controllers\VariationController::class, 'editable'])->name('products.variations.editable');

    // TERMS
    Route::resource('terms', \App\Http\Admin\Controllers\TermController::class, ['except' => 'show']);
    Route::post('terms/order', [\App\Http\Admin\Controllers\TermController::class, 'order'])->name('terms.order');
    Route::get('terms/autocomplete', [\App\Http\Admin\Controllers\TermController::class, 'autocomplete'])->name('terms.autocomplete');

    // EAV
    Route::resource('attributes', \App\Http\Admin\Controllers\AttributeController::class, ['except' => 'show']);
    Route::post('attributes/{attribute}/editable', [\App\Http\Admin\Controllers\AttributeController::class, 'editable'])->name('attributes.editable');
    Route::post('attributes/order', [\App\Http\Admin\Controllers\AttributeController::class, 'order'])->name('attributes.order');
    Route::resource('properties', \App\Http\Admin\Controllers\PropertyController::class, ['except' => 'show']);
    Route::post('properties/{property}/editable', [\App\Http\Admin\Controllers\PropertyController::class, 'editable'])->name('properties.editable');
    Route::post('properties/{property}/image', [\App\Http\Admin\Controllers\PropertyController::class, 'image'])->name('properties.image');
    Route::post('properties/order', [\App\Http\Admin\Controllers\PropertyController::class, 'order'])->name('properties.order');

    // PROFILE
    Route::get('profile', [\App\Http\Admin\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [\App\Http\Admin\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // SUGGESTS
    Route::get('suggest/terms', [\App\Http\Admin\Controllers\SuggestController::class, 'terms'])->name('suggest.terms');
    Route::get('suggest/product-variations', [\App\Http\Admin\Controllers\SuggestController::class, 'productVariations'])->name('suggest.product-variations');
    Route::get('suggest/users', [\App\Http\Admin\Controllers\SuggestController::class, 'users'])->name('suggest.users');

    // ORDERS
    Route::resource('orders', \App\Http\Admin\Controllers\OrderController::class);
    Route::get('orders/{order}/print', [\App\Http\Admin\Controllers\OrderController::class, 'printed'])->name('orders.print');
    Route::get('orders/{order}/email', [\App\Http\Admin\Controllers\OrderController::class, 'email'])->name('orders.email');
    Route::post('orders/{order}/email-send', [\App\Http\Admin\Controllers\OrderController::class, 'emailSend'])->name('orders.email.send');
    Route::post('orders/{order}/discounts', [\App\Http\Admin\Controllers\OrderController::class, 'discountAdd'])->name('orders.discounts.add');
    Route::post('orders/{order}/editable', [\App\Http\Admin\Controllers\OrderController::class, 'editable'])->name('orders.editable');

    // PURCHASES
    Route::post('orders/purchases/{purchase}/editable', [\App\Http\Admin\Controllers\OrderPurchaseController::class, 'editable'])->name('orders.purchases.editable');
    Route::post('orders/{order}/purchases', [\App\Http\Admin\Controllers\OrderPurchaseController::class, 'add'])->name('orders.purchases.add');
    Route::delete('orders/purchases/{purchase}', [\App\Http\Admin\Controllers\OrderPurchaseController::class, 'destroy'])->name('orders.purchases.delete');

    // ORDER PAYMENTS
    Route::get('orders/{order}/payments', [\App\Http\Admin\Controllers\OrderPaymentController::class, 'create'])->name('orders.payments.create');
    Route::get('orders/{order}/create-expense', [\App\Http\Admin\Controllers\OrderPaymentController::class, 'createExpense'])->name('orders.payments.create-expense');
    Route::post('orders/{order}/payments', [\App\Http\Admin\Controllers\OrderPaymentController::class, 'store'])->name('orders.payments.store');
    Route::get('orders/payments/{payment}', [\App\Http\Admin\Controllers\OrderPaymentController::class, 'create'])->name('orders.payments.edit');
    Route::patch('orders/payments/{payment}', [\App\Http\Admin\Controllers\OrderPaymentController::class, 'store'])->name('orders.payments.update');
    Route::delete('orders/payments/{payment}', [\App\Http\Admin\Controllers\OrderPaymentController::class, 'destroy'])->name('orders.payments.destroy');
    Route::post('orders/payments/{payment}/editable', [\App\Http\Admin\Controllers\OrderPaymentController::class, 'editable'])->name('orders.payments.editable');

    // PAYMENTS
    Route::resource('payments', \App\Http\Admin\Controllers\PaymentController::class, ['except' => 'show'])->middleware('can:payment.read');
    Route::post('payments/{payment}/relink', [\App\Http\Admin\Controllers\PaymentController::class, 'relink'])->name('payments.relink');
    Route::post('payments/{payment}/editable', [\App\Http\Admin\Controllers\PaymentController::class, 'editable'])->name('payments.editable');

    // SYSTEM
    Route::view('system/logs', 'admin.system.logs')->name('admin.system.logs');
    Route::view('system/tinker', 'admin.system.tinker');
    Route::get('flogs', [\Ka4ivan\LaravelLogger\Http\Controllers\LogViewerController::class, 'index'])->name('flogs');
    Route::view('logs', 'admin.settings.sections.logs')->name('logs.index');

    // SETTINGS
    Route::redirect('settings', '/admin/settings/common');
    Route::get('settings/{section}', [\App\Http\Admin\Controllers\SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('settings/save', [\App\Http\Admin\Controllers\SettingsController::class, 'save'])->name('settings.save');
});

Route::group([
    'as' => 'unisharp.lfm.',
    'middleware' => [
        'web', 'auth',
        \UniSharp\LaravelFilemanager\Middlewares\CreateDefaultFolder::class,
        \UniSharp\LaravelFilemanager\Middlewares\MultiUser::class]
], function() {
    Route::get('filemanager/jsonitems', [\App\Http\Admin\Controllers\LfmItemsController::class, 'getItems'])->name('getItems');
});
