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
    Route::resource('users', \App\Http\Admin\Controllers\UserController::class)/*->middleware('can:user.read')*/;

//    // ROLES
//    Route::resource('roles', \App\Http\Admin\Controllers\RoleController::class, ['except' => ['show']])->middleware('can:role.read');
//    Route::post('roles/{role}/permission', [\App\Http\Admin\Controllers\RoleController::class, 'permission'])->name('roles.permission')->middleware('can:role.update');
//    Route::post('roles/{role}/event', [\App\Http\Admin\Controllers\RoleController::class, 'event'])->name('roles.event')->middleware('can:role.update');
//    Route::post('roles/{role}/editable', [\App\Http\Admin\Controllers\RoleController::class, 'editable'])->name('roles.editable')->middleware('can:role.update');
//
//    // DOMAINS
//    Route::resource('domains', \App\Http\Admin\Controllers\DomainController::class, ['except' => 'show'])->middleware('can:dev');
//
//    // LEADS
//    Route::resource('leads', \App\Http\Admin\Controllers\LeadController::class)->middleware('can:lead.read');
//    Route::post('leads/import', [\App\Http\Admin\Controllers\LeadController::class, 'import'])->name('leads.import');
//    Route::post('leads/{lead}/editable', [\App\Http\Admin\Controllers\LeadController::class, 'editable'])->name('leads.editable');
//    Route::get('leads/{lead}/convert-to-client-form', [\App\Http\Admin\Controllers\LeadController::class, 'convertToClientForm'])->name('leads.convert-to-client-form');
//    Route::post('leads/{lead}/convert-to-client', [\App\Http\Admin\Controllers\LeadController::class, 'convertToClient'])->name('leads.convert-to-client');
//
//    // PROFILE
//    Route::get('profile', [\App\Http\Admin\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
//    Route::post('profile', [\App\Http\Admin\Controllers\ProfileController::class, 'update'])->name('profile.update');
//    Route::post('profile/notifies', [\App\Http\Admin\Controllers\ProfileController::class, 'notifiesSave'])->name('profile.notifies.save');
//    Route::any('profile/options', [\App\Http\Admin\Controllers\ProfileController::class, 'options'])->name('profile.options');
//
//    // PROFILING
//    Route::resource('profilings', \App\Http\Admin\Controllers\ProfilingController::class, ['except' => 'show']);
//
//    // PAGES
//    Route::resource('pages', \App\Http\Admin\Controllers\PageController::class, ['except' => 'show'])->middleware('can:page.read');
//    Route::post('pages/import', [\App\Http\Admin\Controllers\PageController::class, 'import'])->name('pages.import');
//    Route::get('page/{page}/seo', [\App\Http\Admin\Controllers\PageController::class, 'seoEdit'])->name('pages.seo.edit');
//    Route::post('page/{page}/seo', [\App\Http\Admin\Controllers\PageController::class, 'seoSave'])->name('pages.seo.save');
//    Route::post('pages/{page}/blocks/attach', [\App\Http\Admin\Controllers\PageController::class, 'blocksAttach'])->name('pages.blocks.attach');
//    Route::post('pages/{page}/blocks/detach', [\App\Http\Admin\Controllers\PageController::class, 'blocksDetach'])->name('pages.blocks.detach');
//    Route::post('pages/{page}/blocks/order', [\App\Http\Admin\Controllers\PageController::class, 'blocksOrder'])->name('pages.blocks.order');
//
//    // BLOCKS
//    Route::resource('blocks', \App\Http\Admin\Controllers\BlockController::class)->middleware('can:block.read');
//    Route::get('blocks/{block}/cloning', [\App\Http\Admin\Controllers\BlockController::class, 'cloning'])->name('blocks.cloning');
//    Route::post('blocks/import', [\App\Http\Admin\Controllers\BlockController::class, 'import'])->name('blocks.import');
//
//    // POSTS
//    Route::resource('posts', \App\Http\Admin\Controllers\PostController::class, ['except' => 'show'])->middleware('can:post.read');
//    Route::post('posts/import', [\App\Http\Admin\Controllers\PostController::class, 'import'])->name('posts.import');
//    Route::get('posts/{post}/seo', [\App\Http\Admin\Controllers\PostController::class, 'seoEdit'])->name('posts.seo.edit');
//    Route::post('posts/{post}/seo', [\App\Http\Admin\Controllers\PostController::class, 'seoSave'])->name('posts.seo.save');
//
//    // FAQS
//    Route::resource('faqs', \App\Http\Admin\Controllers\FaqController::class, ['except' => 'show'])->middleware('can:faq.read');
//    Route::post('faqs/import', [\App\Http\Admin\Controllers\FaqController::class, 'import'])->name('faqs.import');
//
//    // COMMENTS
//    Route::resource('comments', \App\Http\Admin\Controllers\Extern\CommentController::class, ['except' => ['show']])->middleware('can:comment.read');
//    Route::post('comments/{comment}/editable', [\App\Http\Admin\Controllers\Extern\CommentController::class, 'editable'])->name('comments.editable');
//    Route::post('comments/import', [\App\Http\Admin\Controllers\Extern\CommentController::class, 'import'])->name('comments.import');
//
//    // TERMS
//    Route::resource('terms', \App\Http\Admin\Controllers\TermController::class, ['except' => 'show']);
//    Route::post('terms/order', [\App\Http\Admin\Controllers\TermController::class, 'order'])->name('terms.order');
//    Route::get('terms/autocomplete', [\App\Http\Admin\Controllers\TermController::class, 'autocomplete'])->name('terms.autocomplete');
//    Route::post('terms/import', [\App\Http\Admin\Controllers\TermController::class, 'import'])->name('terms.import');
//    Route::get('terms/{term}/seo', [\App\Http\Admin\Controllers\TermController::class, 'seoEdit'])->name('terms.seo.edit');
//    Route::post('terms/{term}/seo', [\App\Http\Admin\Controllers\TermController::class, 'seoSave'])->name('terms.seo.save');
//
//    // PRODUCTS
//    Route::resource('products', \App\Http\Admin\Controllers\Shop\ProductController::class, ['except' => 'show'])->middleware('can:product.read');
//    Route::post('products/{product}/attrs', [\App\Http\Admin\Controllers\Shop\ProductController::class, 'attrsSave'])->name('products.attrs.save');
//    Route::post('products/import', [\App\Http\Admin\Controllers\Shop\ProductController::class, 'import'])->name('products.import');
//    Route::post('products/sync', [\App\Http\Admin\Controllers\Shop\ProductController::class, 'sync'])->name('products.sync');
//
//    Route::get('products/{product}/variations', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'create'])->name('products.variations.create')->middleware('can:product.read');
//    Route::post('products/{product}/variations', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'store'])->name('products.variations.store');
//    Route::get('products/variations/{product_variation}/edit', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'edit'])->name('products.variations.edit');
//    Route::get('products/variations/generate/{field}', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'generateValue'])->name('products.variations.generateValue');
//    Route::patch('products/variations/{product_variation}', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'update'])->name('products.variations.update');
//    Route::delete('products/variations/{product_variation}', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'destroy'])->name('products.variations.delete');
//    Route::post('products/variations/{product_variation}/default', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'default'])->name('products.variations.default');
//    Route::post('products/variations/{product_variation}/editable', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'editable'])->name('products.variations.editable');
//    Route::get('products/variations/{product_variation}/cloning', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'cloning'])->name('products.variations.cloning');
//    Route::post('products/variations/task', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'task'])->name('products.variations.task');
//    Route::get('products/variations/{product_variation}/seo', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'seoEdit'])->name('products.variations.seo.edit');
//    Route::post('products/variations/{product_variation}/seo', [\App\Http\Admin\Controllers\Shop\VariationController::class, 'seoSave'])->name('products.variations.seo.save');
//    //Route::post('products/variations/{product_variation}/clone', [\App\Http\Admin\Controllers\Shop\ProductVariationController::class, 'clone'])->name('products.variations.clone');
//
//    // PROMOTIONS
//    Route::resource('promotions', \App\Http\Admin\Controllers\Shop\PromotionController::class, ['except' => 'show'])->middleware('can:promotion.read');
//    Route::get('promotions/{promotion}/seo', [\App\Http\Admin\Controllers\Shop\PromotionController::class, 'seoEdit'])->name('promotions.seo.edit');
//    Route::post('promotions/{promotion}/seo', [\App\Http\Admin\Controllers\Shop\PromotionController::class, 'seoSave'])->name('promotions.seo.save');
//    Route::post('promotions/{promotion}/conditions', [\App\Http\Admin\Controllers\Shop\PromotionController::class, 'conditionsSave'])->name('promotions.conditions.save');
//    Route::post('promocodes/generate', \App\Actions\Promotions\Promocodes\GenerateAction::class)->name('promocodes.generate');
//    Route::post('promocodes', \App\Actions\Promotions\Promocodes\StoreAction::class)->name('promocodes.store');
//    Route::delete('promocodes/{promocode}', \App\Actions\Promotions\Promocodes\DeleteAction::class)->name('promocodes.delete');
//    Route::post('promocodes/{promocode}/editable', \App\Actions\Promotions\Promocodes\EditableActions::class)->name('promocodes.editable');
//
//    // ORDERS
//    Route::resource('orders', \App\Http\Admin\Controllers\Shop\OrderController::class)->middleware('can:order.read');
//    Route::get('orders/{order}/print', [\App\Http\Admin\Controllers\Shop\OrderController::class, 'printed'])->name('orders.print');
//    Route::get('orders/{order}/email', [\App\Http\Admin\Controllers\Shop\OrderController::class, 'email'])->name('orders.email');
//    Route::post('orders/{order}/email-send', [\App\Http\Admin\Controllers\Shop\OrderController::class, 'emailSend'])->name('orders.email.send');
//    Route::post('orders/{order}/discounts', [\App\Http\Admin\Controllers\Shop\OrderController::class, 'discountAdd'])->name('orders.discounts.add');
//    Route::post('orders/{order}/editable', [\App\Http\Admin\Controllers\Shop\OrderController::class, 'editable'])->name('orders.editable');
//
//    // PURCHASES
//    Route::post('orders/purchases/{purchase}/editable', [\App\Http\Admin\Controllers\Shop\OrderPurchaseController::class, 'editable'])->name('orders.purchases.editable');
//    Route::post('orders/{order}/purchases', [\App\Http\Admin\Controllers\Shop\OrderPurchaseController::class, 'add'])->name('orders.purchases.add');
//    Route::delete('orders/purchases/{purchase}', [\App\Http\Admin\Controllers\Shop\OrderPurchaseController::class, 'destroy'])->name('orders.purchases.delete');
//
//    // ORDER PAYMENTS
//    Route::get('orders/{order}/payments', [\App\Http\Admin\Controllers\Shop\OrderPaymentController::class, 'create'])->name('orders.payments.create');
//    Route::get('orders/{order}/create-expense', [\App\Http\Admin\Controllers\Shop\OrderPaymentController::class, 'createExpense'])->name('orders.payments.create-expense');
//    Route::post('orders/{order}/payments', [\App\Http\Admin\Controllers\Shop\OrderPaymentController::class, 'store'])->name('orders.payments.store');
//    Route::get('orders/payments/{payment}', [\App\Http\Admin\Controllers\Shop\OrderPaymentController::class, 'create'])->name('orders.payments.edit');
//    Route::patch('orders/payments/{payment}', [\App\Http\Admin\Controllers\Shop\OrderPaymentController::class, 'store'])->name('orders.payments.update');
//    Route::delete('orders/payments/{payment}', [\App\Http\Admin\Controllers\Shop\OrderPaymentController::class, 'destroy'])->name('orders.payments.destroy');
//    Route::post('orders/payments/{payment}/editable', [\App\Http\Admin\Controllers\Shop\OrderPaymentController::class, 'editable'])->name('orders.payments.editable');
//
//    // PAYMENTRECEIPTS
//    //Route::get('payments/{payment}/paymentreceipts/confirm', [\App\Http\Admin\Controllers\Shop\PaymentreceiptController::class, 'receiptConfirm'])->name('paymentreceipts.confirm');
//    Route::post('payments/{payment}/paymentreceipts/sell', [\App\Http\Admin\Controllers\Shop\PaymentreceiptController::class, 'receiptSell'])->name('paymentreceipts.sell');
//    Route::post('payments/{payment}/paymentreceipts/return', [\App\Http\Admin\Controllers\Shop\PaymentreceiptController::class, 'receiptReturn'])->name('paymentreceipts.return');
//    Route::get('paymentreceipts/{receipt}', [\App\Http\Admin\Controllers\Shop\PaymentreceiptController::class, 'receiptShow'])->name('paymentreceipts.show');
//    Route::get('paymentreceipts/{receipt}/status-sync', [\App\Http\Admin\Controllers\Shop\PaymentreceiptController::class, 'receiptStatusSync'])->name('paymentreceipts.status-sync');
//    Route::get('paymentreceipts/{receipt}/{format}', [\App\Http\Admin\Controllers\Shop\PaymentreceiptController::class, 'receiptVisual'])->name('paymentreceipts.visual');
//
//    // POS TERMINAL
//    Route::get('pos', [\App\Http\Admin\Controllers\Shop\PosController::class, 'create'])->name('pos.create')->middleware('can:pos.manage');
//    Route::post('pos', [\App\Http\Admin\Controllers\Shop\PosController::class, 'store'])->name('pos.store');
//
//    // PAYMENTS
//    Route::resource('payments', \App\Http\Admin\Controllers\Extern\PaymentController::class, ['except' => 'show'])->middleware('can:payment.read');
//    Route::post('payments/{payment}/relink', [\App\Http\Admin\Controllers\Extern\PaymentController::class, 'relink'])->name('payments.relink');
//    Route::post('payments/{payment}/editable', [\App\Http\Admin\Controllers\Extern\PaymentController::class, 'editable'])->name('payments.editable');
//
//    // ORDERSENDINGS
//    Route::get('ordersendings/ukrposhta-address', [\App\Http\Admin\Controllers\Shop\OrdersendingController::class, 'getAddress'])->name('ukrposhta.address'); // Укрпошта
//    Route::get('ordersendings/{ordersending}/sticker', [\App\Http\Admin\Controllers\Shop\OrdersendingController::class, 'sticker'])->name('ordersendings.sticker');
//    Route::resource('ordersendings', \App\Http\Admin\Controllers\Shop\OrdersendingController::class);
//    Route::resource('ordersendings-ttn', \App\Http\Admin\Controllers\Shop\OrdersendingTtnController::class);
//
//    // EAV
//    Route::resource('attributes', \App\Http\Admin\Controllers\Eav\AttributeController::class, ['except' => 'show'])->middleware('can:product.read');
//    Route::post('attributes/{attribute}/editable', [\App\Http\Admin\Controllers\Eav\AttributeController::class, 'editable'])->name('attributes.editable');
//    Route::post('attributes/order', [\App\Http\Admin\Controllers\Eav\AttributeController::class, 'order'])->name('attributes.order');
//    Route::post('attributes/import', [\App\Http\Admin\Controllers\Eav\AttributeController::class, 'import'])->name('attributes.import');
//    Route::resource('properties', \App\Http\Admin\Controllers\Eav\PropertyController::class, ['except' => 'show']);
//    Route::post('properties/{property}/editable', [\App\Http\Admin\Controllers\Eav\PropertyController::class, 'editable'])->name('properties.editable');
//    Route::post('properties/{property}/image', [\App\Http\Admin\Controllers\Eav\PropertyController::class, 'image'])->name('properties.image');
//    Route::post('properties/order', [\App\Http\Admin\Controllers\Eav\PropertyController::class, 'order'])->name('properties.order');
//    Route::post('properties/import', [\App\Http\Admin\Controllers\Eav\PropertyController::class, 'import'])->name('properties.import');
//
//    // SUPPLIERS
//    Route::resource('suppliers', \App\Http\Admin\Controllers\SupplierController::class, ['except' => 'show'])->middleware('can:warehouse.read');;
//    Route::post('suppliers/import', [\App\Http\Admin\Controllers\SupplierController::class, 'import'])->name('suppliers.import');
//
//    // WAREHOUSES
//    Route::group(['prefix' => 'warehouses'], function () {
//        Route::resource('/', \App\Http\Admin\Controllers\WarehouseController::class, ['except' => 'show']);
//
//        Route::get('variations', [\App\Http\Admin\Controllers\WareoperationController::class, 'variations']);
//        Route::get('{variation}/history', [\App\Http\Admin\Controllers\WareoperationController::class, 'history'])->name('warehouses.variations.history');
//
//        Route::get('postings', [\App\Http\Admin\Controllers\WareoperationController::class, 'postings']);
//        Route::post('postings', [\App\Http\Admin\Controllers\WareoperationController::class, 'postingAdd'])->name('warehouses.postings.add');
//        Route::get('postings/{operation}', [\App\Http\Admin\Controllers\WareoperationController::class, 'postingShow'])->name('warehouses.postings.show');
//
//        Route::get('outcomes', [\App\Http\Admin\Controllers\WareoperationController::class, 'outcomes']);
//        Route::post('outcomes', [\App\Http\Admin\Controllers\WareoperationController::class, 'outcomeAdd'])->name('warehouses.outcomes.add');
//        Route::get('outcomes/{operation}', [\App\Http\Admin\Controllers\WareoperationController::class, 'outcomeShow'])->name('warehouses.outcomes.show');
//
//        Route::get('returneds', [\App\Http\Admin\Controllers\WareoperationController::class, 'returneds']);
//        Route::post('returneds', [\App\Http\Admin\Controllers\WareoperationController::class, 'returnedAdd'])->name('warehouses.returneds.add');
//        Route::get('returneds/{operation}', [\App\Http\Admin\Controllers\WareoperationController::class, 'returnedShow'])->name('warehouses.returneds.show');
//
//        Route::get('inventories', [\App\Http\Admin\Controllers\WareoperationController::class, 'inventories']);
//        Route::post('inventories', [\App\Http\Admin\Controllers\WareoperationController::class, 'inventoryAdd'])->name('warehouses.inventories.add');
//        Route::post('inventories/{operation}/complete', [\App\Http\Admin\Controllers\WareoperationController::class, 'inventoryComplete'])->name('warehouses.inventories.complete');
//        Route::get('inventories/{operation}', [\App\Http\Admin\Controllers\WareoperationController::class, 'inventoryShow'])->name('warehouses.inventories.show');
//
//        Route::delete('warevariation/{warevariation}', [\App\Http\Admin\Controllers\WareoperationController::class, 'warevariationDelete'])->name('warehouses.warevariation.delete');
//
//        Route::get('moves', [\App\Http\Admin\Controllers\WareoperationController::class, 'moves']);
//        Route::post('moves', [\App\Http\Admin\Controllers\WareoperationController::class, 'movesAdd'])->name('warehouses.moves.add');
//        Route::get('moves/{operation}', [\App\Http\Admin\Controllers\WareoperationController::class, 'moveShow'])->name('warehouses.moves.show');
//
//        Route::post('wareoperation/{wareoperation}/status', [\App\Http\Admin\Controllers\WareoperationController::class, 'wareoperationStatus'])->name('warehouses.wareoperations.status');
//        Route::get('wareoperation/create', [\App\Http\Admin\Controllers\WareoperationController::class, 'create'])->name('warehouses.wareoperations.create');
//        Route::get('wareoperation/{wareoperation}/edit', [\App\Http\Admin\Controllers\WareoperationController::class, 'edit'])->name('warehouses.wareoperations.edit');
//        Route::delete('wareoperation/{wareoperation}', [\App\Http\Admin\Controllers\WareoperationController::class, 'destroy'])->name('warehouses.wareoperations.destroy');
//
//        Route::post('import', [\App\Http\Admin\Controllers\WarehouseController::class, 'import'])->name('warehouses.import');
//        Route::resource('warehouses', \App\Http\Admin\Controllers\WarehouseController::class, ['except' => 'show']);
//
//        Route::get('postings', [\App\Http\Admin\Controllers\WareoperationController::class, 'postings']);
//        Route::post('postings', [\App\Http\Admin\Controllers\WareoperationController::class, 'postingAdd'])->name('warehouses.postings.add');
//        Route::get('postings/{operation}', [\App\Http\Admin\Controllers\WareoperationController::class, 'postingShow'])->name('warehouses.postings.show');
//
//        Route::get('orders', [\App\Http\Admin\Controllers\WareoperationController::class, 'orders']);
//        Route::get('orders/{order}', [\App\Http\Admin\Controllers\WareoperationController::class, 'orderShow'])->name('warehouses.orders.show');
//    })->middleware('can:warehouse.read');
//
//    Route::group(['prefix' => 'services'], function () {
//        Route::post('checkbox/products-sync', [\App\Http\Admin\Controllers\Services\CheckboxController::class, 'productsSync'])->name('services.checkbox.products-sync');
//        Route::get('checkbox/webhook-check', \App\Actions\Checkbox\WebhookCheckAction::class)->name('services.checkbox.webhook-check');
//        Route::post('checkbox/webhook-set', \App\Actions\Checkbox\WebhookSetAction::class)->name('services.checkbox.webhook-set');
//    });
//
//    Route::resource('findocuments', \App\Http\Admin\Controllers\FindocumentController::class)->middleware('can:findocument.read');;
//    Route::post('findocuments/import', [\App\Http\Admin\Controllers\FindocumentController::class, 'import'])->name('findocuments.import');
//
//
//    // SEO METATAGS
//    Route::resource('seos', \App\Http\Admin\Controllers\SeoController::class, ['except' => ['show']])->middleware('can:seo.manage');
//    Route::post('seos/import', [\App\Http\Admin\Controllers\SeoController::class, 'import'])->name('seos.import');
//
//    // REDIRECTS TODO: Feature
//    Route::resource('redirects', \App\Http\Admin\Controllers\RedirectController::class, ['except' => ['show']]);
//    Route::post('redirects/{redirect}/editable', [\App\Http\Admin\Controllers\RedirectController::class, 'editable'])->name('redirects.editable');
////    Route::post('redirects/apply', [\App\Http\Admin\Controllers\RedirectController::class, 'apply'])->name('redirects.apply');
//    Route::post('redirects/import', [\App\Http\Admin\Controllers\RedirectController::class, 'import'])->name('redirects.import');
//
//    // MENU
//    Route::get('menu', [\App\Http\Admin\Controllers\MenuitemController::class, 'index'])->name('menu.index')->middleware('can:menu.read');
//    Route::resource('menu-items', \App\Http\Admin\Controllers\MenuitemController::class, ['except' => ['show']])->middleware('can:menu.read');
//    Route::post('menu-items/order', [\App\Http\Admin\Controllers\MenuitemController::class, 'order'])->name('menu-items.order');
//    Route::post('menu-items/import', [\App\Http\Admin\Controllers\MenuitemController::class, 'import'])->name('menu-items.import');
//    Route::post('menu-items/import-terms', [\App\Http\Admin\Controllers\MenuitemController::class, 'importTerms'])->name('menu-items.import-terms');
//
//    // CHAT
//    Route::get('chats', [\App\Http\Admin\Controllers\ChatController::class, 'index'])->name('chats.index');
//    Route::get('chats/{chat}', [\App\Http\Admin\Controllers\ChatController::class, 'show'])->name('chats.show');
//    Route::post('chats/{user}', [\App\Http\Admin\Controllers\ChatController::class, 'make'])->name('chats.make');
//    Route::post('chats/{chat}/message', [\App\Http\Admin\Controllers\ChatController::class, 'message'])->name('chats.message');
//
//    // SYSTEM SERVICES
//    Route::view('logs', 'admin.settings.sections.logs')->name('logs.index');
//    Route::get('flogs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('flogs');
//    Route::view('tinker', 'admin.settings.sections.tinker')->name('tinker.index')->middleware('can:dev');
//
//    // SETTINGS
//    Route::redirect('settings', '/admin/settings/common');
//    Route::get('settings/{section}', [\App\Http\Admin\Controllers\SettingsController::class, 'edit'])->name('settings.edit');
//    Route::post('settings/save', [\App\Http\Admin\Controllers\SettingsController::class, 'save'])->name('settings.save');
//
//    // TRANSLATIONS
//    Route::resource('translations', \App\Http\Admin\Controllers\TranslationController::class, ['except' => ['create', 'edit', 'show']]);
//    Route::post('translations/{translation}/editable', [\App\Http\Admin\Controllers\TranslationController::class, 'editable'])->name('translations.editable');
//    Route::post('translations/import', [\App\Http\Admin\Controllers\TranslationController::class, 'import'])->name('translations.import');
//
//    Route::view('system/tinker', 'admin.system.tinker')->middleware('can:dev');
//    Route::view('system/logs', 'admin.system.logs')->middleware('can:dev');
//    Route::view('system/vars', 'admin.system.vars')->middleware('can:dev');
//    Route::view('system/info', 'admin.system.info')->middleware('can:dev');
//    Route::view('system/docs', 'admin.system.docs')->middleware('can:dev');
//    Route::view('system/horizon', 'admin.system.horizon')->middleware('can:dev');
//
//    // ITEMS
//    Route::resource('items', \App\Http\Admin\Controllers\ItemController::class, ['except' => ['show']]);
//    Route::post('items/{item}/editable', [\App\Http\Admin\Controllers\ItemController::class, 'editable'])->name('items.editable');
//    Route::post('items/order', [\App\Http\Admin\Controllers\ItemController::class, 'order'])->name('items.order');
//    Route::get('items/export', [\App\Http\Admin\Controllers\ItemController::class, 'export'])->name('items.export');
//    Route::post('items/import', [\App\Http\Admin\Controllers\ItemController::class, 'import'])->name('items.import');
//
//    // SERVICES
//    Route::resource('services', \App\Http\Admin\Controllers\ServiceController::class, ['except' => ['index', 'show', 'create', 'edit']]);
//
//    // LOCATIONS
////    Route::get('countries', [\App\Http\Admin\Controllers\Location\CountryController::class, 'index']);
////    Route::get('currencies', [\App\Http\Admin\Controllers\Location\CurrencyController::class, 'index']);
////    Route::get('locales', [\App\Http\Admin\Controllers\Location\LocaleController::class, 'index']);
////    Route::get('timezones', [\App\Http\Admin\Controllers\Location\TimezonesController::class, 'index']);
//
//    // STATISTICS
//    Route::redirect('statistics/', '/admin/general');
//    Route::get('statistics/general', [\App\Http\Admin\Controllers\StatisticController::class, 'generalIndicators'])->middleware('can:statistic.read');
//    Route::get('statistics/sales', [\App\Http\Admin\Controllers\StatisticController::class, 'sales'])->middleware('can:statistic.read');
//
//    // SUGGESTS
//    Route::get('suggest/terms', [\App\Http\Admin\Controllers\SuggestController::class, 'terms'])->name('suggest.terms');
//    Route::get('suggest/products', [\App\Http\Admin\Controllers\SuggestController::class, 'products'])->name('suggest.products');
//    Route::get('suggest/product-variations', [\App\Http\Admin\Controllers\SuggestController::class, 'productVariations'])->name('suggest.product-variations');
//    Route::get('suggest/users', [\App\Http\Admin\Controllers\SuggestController::class, 'users'])->name('suggest.users');
//    Route::get('suggest/posts', [\App\Http\Admin\Controllers\SuggestController::class, 'posts'])->name('suggest.posts');
//    Route::get('suggest/pages', [\App\Http\Admin\Controllers\SuggestController::class, 'pages'])->name('suggest.pages');
//    Route::get('suggest/orders', [\App\Http\Admin\Controllers\SuggestController::class, 'orders'])->name('suggest.orders');
//    Route::get('suggest/comments', [\App\Http\Admin\Controllers\SuggestController::class, 'comments'])->name('suggest.comments');
//    Route::get('suggest/promotions', [\App\Http\Admin\Controllers\SuggestController::class, 'promotions'])->name('suggest.promotions');
//    Route::get('suggest/google-categories', [\App\Http\Admin\Controllers\SuggestController::class, 'googleCategories'])->name('suggest.google-categories'); // Категорії Google Merchant
//    Route::get('suggest/rozetka-categories', [\App\Http\Admin\Controllers\SuggestController::class, 'rozetkaCategories'])->name('suggest.rozetka-categories'); // Категорії Google Merchant
//
//    // SUGGESTS UKRPOSHTA
//    Route::get('suggest/ukrposhta-regions', [\App\Http\Admin\Controllers\SuggestController::class, 'regionsUkrposhta'])->name('suggest.ukrposhta.regions'); // Укрпошта(Області)
//    Route::get('suggest/ukrposhta-cities', [\App\Http\Admin\Controllers\SuggestController::class, 'citiesUkrposhta'])->name('suggest.ukrposhta.cities'); // Укрпошта(Міста)
//    Route::get('suggest/ukrposhta-departments', [\App\Http\Admin\Controllers\SuggestController::class, 'departmentsUkrposhta'])->name('suggest.ukrposhta.departments'); // Укрпошта(Відділення)
});

//Route::group([
//    'as' => 'unisharp.lfm.',
//    'middleware' => [
//        'web', 'auth',
//        \UniSharp\LaravelFilemanager\Middlewares\CreateDefaultFolder::class,
//        \UniSharp\LaravelFilemanager\Middlewares\MultiUser::class]
//], function() {
//    Route::get('filemanager/jsonitems', [\App\Http\Admin\Controllers\LfmItemsController::class, 'getItems'])->name('getItems');
//});
