<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => [
    \App\Http\Middleware\SetClientDomain::class,
]], function () {

    require __DIR__.'/api-auth.php';

    // MY/PROFILE
    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'my'], function () {
        Route::get('profile', [\App\Http\Client\Api\Controllers\ProfileController::class, 'edit']);
        Route::post('profile', [\App\Http\Client\Api\Controllers\ProfileController::class, 'update']);
        Route::delete('profile', [\App\Http\Client\Api\Controllers\ProfileController::class, 'delete']);

        Route::get('orders', [\App\Http\Client\Api\Controllers\OrderController::class, 'index']);
    });

    // PAGES
    Route::get('pages/{page:slug}', [\App\Http\Client\Api\Controllers\PageController::class, 'show']);

    // PRODUCT-VARIATIONS
    Route::get('shop/variations', [\App\Http\Client\Api\Controllers\ShopController::class, 'variations']);
    Route::get('shop/variations/facet', [\App\Http\Client\Api\Controllers\ShopController::class, 'facet']);
    Route::get('shop/variations/{variation:slug}', [\App\Http\Client\Api\Controllers\ShopController::class, 'variation']);

    Route::get('shop/categories', [\App\Http\Client\Api\Controllers\ShopController::class, 'categories']);
    Route::get('shop/categories/tree/view', [\App\Http\Client\Api\Controllers\ShopController::class, 'categoriesTree']);
    Route::get('shop/categories/{category:slug}', [\App\Http\Client\Api\Controllers\ShopController::class, 'category']);

    // SUGGEST
    Route::controller(\App\Http\Client\Api\Controllers\SuggestController::class)->group(function() {

        Route::get('suggest/variables', 'variables');
        Route::get('suggest/static-lists', 'staticLists');
        Route::get('suggest/vocabularies', 'vocabularies');
        Route::get('suggest/tracker', 'tracker');
        Route::get('suggest/socialite', 'socialite');
        Route::get('suggest/incoming', 'incoming');
        Route::get('suggest/countries', 'countries');
        Route::get('suggest/payment-methods', 'paymentMethods');
        Route::get('suggest/shipping-methods', 'shippingMethods');
        Route::get('suggest/terms/{vocabulary}', 'terms');

        // https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a1c42723-8512-11ec-8ced-005056b2dbe1
        Route::get('suggest/shipping/novaposhta/settlements', 'novaposhtaSettlements'); // населені пункти
        Route::get('suggest/shipping/novaposhta/offices', 'novaposhtaOffices'); // відділення
        Route::get('suggest/shipping/novaposhta/lockers', 'novaposhtaLockers'); // поштомати

        // https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a1c42723-8512-11ec-8ced-005056b2dbe1
        Route::get('suggest/shipping/ukrposhta/regions', 'ukrposhtaRegions'); // області
        Route::get('suggest/shipping/ukrposhta/cities', 'ukrposhtaCities'); // населені пункти
        Route::get('suggest/shipping/ukrposhta/departments', 'ukrposhtaDepartments'); // відділення
    });


    // CART
    Route::controller(\App\Http\Client\Api\Controllers\CartController::class)->prefix('cart')->group(function () {
        Route::get('/', 'purchases');
        Route::get('/checkout', 'checkoutForm');
        Route::post('promocode/add', 'promocode');
        Route::post('promocode/remove', 'promocodeRemove');

        Route::post('{variation}/add', 'add');
        Route::post('{purchase}/remove', 'remove');
        Route::post('sync', 'sync');
        Route::post('checkout', 'checkout');
        Route::get('order/{order}', 'order');
        Route::post('order/{order}/repeat', 'repeat');
    });

    // CONTENT
    Route::get('app/glob', [\App\Http\Client\Api\Controllers\AppController::class, 'glob']);
    Route::get('app/content', [\App\Http\Client\Api\Controllers\AppController::class, 'glob']); // TODO: Deprecated!
    Route::get('app/slug/{slug}', [\App\Http\Client\Api\Controllers\AppController::class, 'slug']);
    Route::get('app/translations', [\App\Http\Client\Api\Controllers\AppController::class, 'translations']);
    Route::get('app/menu/catalog', [\App\Http\Client\Api\Controllers\AppController::class, 'menuCatalog']);

    // BLOCK
//    Route::get('blocks', [\App\Http\Client\Api\Controllers\BlockController::class, 'index']);
//    Route::get('blocks/{block:slug}', [\App\Http\Client\Api\Controllers\BlockController::class, 'show']);
});





