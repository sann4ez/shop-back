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
