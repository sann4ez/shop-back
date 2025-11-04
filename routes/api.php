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

//Route::group(['middleware' => [
//    \App\Http\Middleware\SetClientDomain::class,
//]], function () {
//
//    require __DIR__.'/api-auth.php';
//
//    // MY/PROFILE
//    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'my'], function () {
//        Route::get('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'edit']);
//        Route::post('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'update']);
//        Route::delete('profile', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'delete']);
//        Route::post('profile/password', [\App\Http\Client\Api\Controllers\My\ProfileController::class, 'updatePassword']);
//
//        Route::get('orders', [\App\Http\Client\Api\Controllers\My\OrderController::class, 'index']);
//        Route::post('orders/{order:number}/payment', [\App\Http\Client\Api\Controllers\My\OrderController::class, 'paymentLink']);
//        Route::post('orders/{order:number}/canceled', [\App\Http\Client\Api\Controllers\My\OrderController::class, 'canceled']);
//
//        Route::resource('profilings', \App\Http\Client\Api\Controllers\My\ProfilingController::class);
//
//        // FAVORITES
//        Route::group(['prefix' => 'favorites'], function () {
//            Route::get('variations', [\App\Http\Client\Api\Controllers\My\FavoriteController::class, 'variations']);
//            Route::post('variations/{variation}', [\App\Http\Client\Api\Controllers\My\FavoriteController::class, 'variation']);
//        });
//    });
//
//    // PAGES
//    Route::get('pages/{page:slug}', [\App\Http\Client\Api\Controllers\PageController::class, 'show']);
//
//    // PRODUCT-VARIATIONS
//    Route::get('shop/variations', [\App\Http\Client\Api\Controllers\ShopController::class, 'variations']);
//    Route::get('shop/variations/revieweds', [\App\Http\Client\Api\Controllers\ShopController::class, 'revieweds']);
//    Route::get('shop/variations/facet', [\App\Http\Client\Api\Controllers\ShopController::class, 'facet']);
//    Route::get('shop/variations/{variation:slug}', [\App\Http\Client\Api\Controllers\ShopController::class, 'variation']);
//
//    Route::get('shop/categories', [\App\Http\Client\Api\Controllers\ShopController::class, 'categories']);
//    Route::get('shop/categories/tree/view', [\App\Http\Client\Api\Controllers\ShopController::class, 'categoriesTree']);
//    Route::get('shop/categories/{category:slug}', [\App\Http\Client\Api\Controllers\ShopController::class, 'category']);
//    Route::get('shop/brands', [\App\Http\Client\Api\Controllers\ShopController::class, 'brands']);
//    Route::get('shop/brands/{brand:slug}', [\App\Http\Client\Api\Controllers\ShopController::class, 'brand']);
//
//    // COMMENTS
//    Route::get('comments/products/{product:id}/', [\App\Http\Client\Api\Controllers\CommentController::class, 'listProduct']);
//    Route::post('comments/products/{product:id}', [\App\Http\Client\Api\Controllers\CommentController::class, 'addProduct']);
//    Route::get('comments/posts/{post:id}/', [\App\Http\Client\Api\Controllers\CommentController::class, 'listPost']);
//    Route::post('comments/posts/{post:id}', [\App\Http\Client\Api\Controllers\CommentController::class, 'addPost']);
//    Route::get('comments/{comment:id}', [\App\Http\Client\Api\Controllers\CommentController::class, 'show']);
//    Route::post('comments', [\App\Http\Client\Api\Controllers\CommentController::class, 'comment']);
//
//    // COMPARISON
//    Route::post('/comparisons/{variation:id}/toggle', [\App\Http\Client\Api\Controllers\ComparisonController::class, 'toggle']);
//    Route::get('/comparisons/categories', [\App\Http\Client\Api\Controllers\ComparisonController::class, 'categories']);
//    Route::post('/comparisons/categories/{term}/delete', [\App\Http\Client\Api\Controllers\ComparisonController::class, 'categoriesDelete']);
//    Route::get('/comparisons/categories/variations', [\App\Http\Client\Api\Controllers\ComparisonController::class, 'categoriesVariations']);
//    Route::get('/comparisons/all', [\App\Http\Client\Api\Controllers\ComparisonController::class, 'variationsAll']);
//    Route::get('/comparisons/{comparison:id}', [\App\Http\Client\Api\Controllers\ComparisonController::class, 'variations']);
//
//    // BLOG
//    Route::get('/blog/posts', [\App\Http\Client\Api\Controllers\BlogController::class, 'index']);
//    Route::get('/blog/posts/{post:slug}', [\App\Http\Client\Api\Controllers\BlogController::class, 'show']);
//    Route::get('blog/categories', [\App\Http\Client\Api\Controllers\BlogController::class, 'categories']);
//    Route::get('blog/categories/tree/view', [\App\Http\Client\Api\Controllers\BlogController::class, 'categoriesTree']);
//    Route::get('blog/categories/{category:slug}', [\App\Http\Client\Api\Controllers\BlogController::class, 'category']);
//
//    // FAQS
//    Route::get('/faqs', [\App\Http\Client\Api\Controllers\FaqController::class, 'index']);
//    Route::get('/faqs/categories', [\App\Http\Client\Api\Controllers\FaqController::class, 'categories']);
//    Route::get('/faqs/{faq:slug}', [\App\Http\Client\Api\Controllers\FaqController::class, 'show']);
//    Route::get('/categories/faqs/view', [\App\Http\Client\Api\Controllers\FaqController::class, 'view']);
//    Route::get('/faqs/categories/{category:slug}', [\App\Http\Client\Api\Controllers\FaqController::class, 'category']);
//
//    // TERMS TODO: Deprecated
////    Route::get('terms', [\App\Http\Client\Api\Controllers\TermController::class, 'index']);
////    Route::get('terms/{term:slug}', [\App\Http\Client\Api\Controllers\TermController::class, 'show']);
//
//    // LEADS
//    Route::post('leads', [\App\Http\Client\Api\Controllers\LeadController::class, 'add']);
//
//    // MEDIA
//    Route::post('media', [\App\Http\Client\Api\Controllers\MediaController::class, 'upload']);
//    Route::delete('media/{media}', [\App\Http\Client\Api\Controllers\MediaController::class, 'delete']);
//
//    // SUGGEST
//    Route::controller(\App\Http\Client\Api\Controllers\SuggestController::class)->group(function() {
//
//        // TODO: Deprecated
//        Route::get('suggest/variables', 'variables');
//        Route::get('suggest/static-lists', 'staticLists');
//        Route::get('suggest/vocabularies', 'vocabularies');
//        Route::get('suggest/tracker', 'tracker');
//        Route::get('suggest/socialite', 'socialite');
//        Route::get('suggest/incoming', 'incoming');
//        Route::get('suggest/countries', 'countries');
//        Route::get('suggest/payment-methods', 'paymentMethods');
//        Route::get('suggest/shipping-methods', 'shippingMethods');
//        Route::get('suggest/terms/{vocabulary}', 'terms');
//
//        // https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a1c42723-8512-11ec-8ced-005056b2dbe1
//        Route::get('suggest/shipping/novaposhta/settlements', 'novaposhtaSettlements'); // населені пункти
//        Route::get('suggest/shipping/novaposhta/offices', 'novaposhtaOffices'); // відділення
//        Route::get('suggest/shipping/novaposhta/lockers', 'novaposhtaLockers'); // поштомати
//
//        // https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a1c42723-8512-11ec-8ced-005056b2dbe1
//        Route::get('suggest/shipping/ukrposhta/regions', 'ukrposhtaRegions'); // області
//        Route::get('suggest/shipping/ukrposhta/cities', 'ukrposhtaCities'); // населені пункти
//        Route::get('suggest/shipping/ukrposhta/departments', 'ukrposhtaDepartments'); // відділення
//    });
//
//
//    // CART
//    Route::controller(\App\Http\Client\Api\Controllers\CartController::class)->prefix('cart')->group(function () {
//        Route::get('/', 'purchases');
//        Route::get('/checkout', 'checkoutForm');
//        Route::post('promocode/add', 'promocode');
//        Route::post('promocode/remove', 'promocodeRemove');
//
//        Route::post('{variation}/add', 'add');
//        Route::post('{purchase}/remove', 'remove');
//        Route::post('sync', 'sync');
//        Route::post('checkout', 'checkout');
//        Route::get('order/{order}', 'order');
//        Route::post('order/{order}/repeat', 'repeat');
//    });
//
//    // PROMOTIONS
//    Route::controller(\App\Http\Client\Api\Controllers\PromotionController::class)->group(function () {
//        Route::get('promotions', 'index');
//        Route::get('promotions/{promotion:slug}', 'show');
//    });
//
//    // CONTENT
//    Route::get('app/glob', [\App\Http\Client\Api\Controllers\AppController::class, 'glob']);
//    Route::get('app/content', [\App\Http\Client\Api\Controllers\AppController::class, 'glob']); // TODO: Deprecated!
//    Route::get('app/slug/{slug}', [\App\Http\Client\Api\Controllers\AppController::class, 'slug']);
//    Route::get('app/translations', [\App\Http\Client\Api\Controllers\AppController::class, 'translations']);
//    Route::get('app/menu/catalog', [\App\Http\Client\Api\Controllers\AppController::class, 'menuCatalog']);
//
//    // BLOCK
//    Route::get('blocks', [\App\Http\Client\Api\Controllers\BlockController::class, 'index']);
//    Route::get('blocks/{block:slug}', [\App\Http\Client\Api\Controllers\BlockController::class, 'show']);
//
//    // SITEMAP
//    Route::get('sitemap', \App\Actions\Seo\SitemapGenerateAction::class); // TODO: Deprecated! Vovna?
//
//    Route::get('seo/sitemap', \App\Actions\Seo\SitemapGenerateAction::class);
//    Route::get('seo/imagemap', \App\Actions\Seo\ImagemapGenerateAction::class);
//});
//




