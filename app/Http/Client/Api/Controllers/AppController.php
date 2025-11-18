<?php

namespace App\Http\Client\Api\Controllers;

use App\Http\Client\Api\Resources\TermResource;
use App\Models\User;
use App\Models\Attribute;
use App\Models\Term;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

final class AppController extends Controller
{
    /**
     * @api {get} /api/app/glob 01. Глобальні дані
     * @apiVersion 1.0.0
     * @apiName AppContent
     * @apiGroup App
     *
     * @apiParam {String} [path] URL поточної сторінки (нд: /brand/lego) - для SEO
     *
     * @apiDescription Список меню, блоки, локаль, користувач, корзина, сео, улюблені, порівняння, профілізація і т.д.
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     * {
     *    "user": {
     *       "id": "a3e22e6-329e-46ba-948f-670dbea5eb1f",
     *       "email": "bob@app.com",
     *       "fullname": "Bob Null",
     *    },
     *    "blocks": ["contacts":{"viber": "+380999999999"}],
     *    "menu": [
     *      "header": {
     *          "slug": "header",
     *          "name": "Шапка",
     *          "items": [
     *              {"id": "4", "name": "Доставка", "path": "/delivery", "target": null, "children": []},
     *              {"id": "5", "name": "Про нас", "path": "/about", "target": null, "children": []}
     *          ]
     *      }
     *    ],
     *    "cart": {"quantity": 3, "total": 1304},
     *    "seo": {"metatags": {"title": "SEO для url-шляху - параметр path", "description": "Головна сторінка магазину - купити"}, "h1": "Головна сторінка сайту", "text": "Якийсь SEO-текст", "faq":[]},
     *    "locales": {"current": "uk", "list": [{"name": "English", "native": "English", "title": "en","key": "en"},{"name": "Ukrainian", "native": "українська", "title": "ua","key": "uk"}]},
     *    "profilings": {"current": null, "list": [{"id": 13, "main": {"name": "Кіт Черч"}}]}
     *    "socialite": [{"key": "google", "name": "Google"}, {"key": "facebook", "name": "Facebook"}]
     * }
     *
     */
    public function glob(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $profilings = $user?->profilings ?: collect();

        return response()->json([
            // стилі, шрифти, іконки
            'assets' => [],

            // глобальні блоки (sblocks)
            'blocks' => \Block::getBlocksResource(\Domain::getOpt('menu.blocks', []), true),

            // основні дані корзини
            'cart' => [
                'quantity' => \Cart::getQty(),
                'total' => \Cart::totalSum(),
            ],

            // основні дані порівнянь
            'comparisons' => [
                'categories' => \Comparison::getQtyCategories(),
                'variations' => \Comparison::getQty(),
            ],

            // обрані
            'favorites' => [
                'quantity' => \Favorite::getQty(),
            ],

            // поточна локаль
            'locale' => app()->getLocale(),

            // список підтримуваних локалей в системі
            'locales' => Arr::map(\Domain::getSupportedLocales(), fn($e) => Arr::only($e, ['native', 'name', 'title', 'key'])),

            // списко меню та їх пунктів
            'menu' => $this->getMenus(),

            // провайдери socialite для авторизації
            'socialite' => array_values(Socialite::providersList(['key', 'name'], 'key')),

            // профілізація
            'profilings' => [
                'current' => $profilings->first()?->id,
                'list' => $user ? ProfilingListResource::collection($profilings) : [],
            ],

            // SEO-дані
            'seo' => $this->getSeo($request),

            // дані авторизованого користувача
            'user' => $user ? [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'role' => $user->roles->first()?->name, // TODO Vovna
                'discount' => $user->discount,
            ] : null,
        ]);
    }

    /**
     * @return array
     */
    protected function getMenus(): array
    {
        $menuItems = Menuitem::with('translations', 'children', 'model.translations')->get()->toTree();

        $res = [];
        foreach (Menuitem::menusList() as $menu) {
            $menuSlug = Arr::get($menu, 'key');
            $res[$menuSlug] = [
                'slug' => $menuSlug,
                'name' => Arr::get($menu, 'name'),
            ];

            $res[$menuSlug]['items'] = MenuitemResource::collection($menuItems->whereIn('menu', $menuSlug));
        }

        return $res;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getSeo(Request $request): null|array
    {
        $path = get_path_without_host($request->path, true);
        \Seo::setPath($path);
        $seopath = \Seo::getSeopath();

        if ($path && $seopath) {
            return SeoResource::make($seopath)->toArray($request);
        }

        return null;
    }

    /**
     * @api {get} /api/app/slug/{slug} 02. Сутність по слагу
     * @apiVersion 1.0.0
     * @apiName AppSlug
     * @apiGroup App
     *
     * @apiDescription Отримати сутність по слагу.
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "id": "8aa42477-60f2-45bc-b829-ff2f255f5194",
     *          "entity": "page",
     *          "slug": "contacts",
     *          "name": "Контакти",
     *          "body": null,
     *          "template": "contacts"
     *      },
     *      "seo": {},
     *      "blocks": [],
     *      "sblocks": []
     *  }
     */
    public function slug(Request $request, string $slug)
    {
        // Якщо передають Attribute/Property
        if (str_contains($slug, '=')) {
            $this->checkAttributeProperties($slug);

            return response()->json([
                'data' => [
                    'entity' => 'product_categories',
                ]
            ]);
        }

        $models = [
            \App\Models\Term::class,
            \App\Models\Page::class,
            \App\Models\ProductVariation::class,
        ];

        /** @var Model $model */
        foreach ($models as $model) {

            $record = $model::where('slug', $slug)->first();

            if ($record) {
                switch ($model) {
                    case \App\Models\Page::class:
                        return app(PageController::class)->show($request, $slug);
                    case \App\Models\ProductVariation::class:
                        return app(ShopController::class)->variation($request, $record);
                    case \App\Models\Term::class:
                        if ($record->vocabulary === Term::VOCABULARY_PRODUCT_CATEGORIES) {
                            return app(ShopController::class)->category($request, $record);
                        } elseif ($record->vocabulary === Term::VOCABULARY_BRANDS) {
                            return app(ShopController::class)->brand($request, $record);
                        } elseif ($record->vocabulary === Term::VOCABULARY_TAGS) {
                            return TermResource::make($record);
                        }
                }
            }
        }

        \abort(404);
    }

    protected function checkAttributeProperties(string $slug): void
    {
        // Парсимо slug на атрибути і проперті
        $filters = collect(explode(';', $slug))
            ->mapWithKeys(function ($part) {
                [$attr, $values] = explode('=', $part, 2);
                return [$attr => collect(explode(',', $values))->sort()->values()];
            });

        // Сортуємо атрибути і проперті по алфавіту
        $sortFilters = $filters
            ->sortKeys()
            ->map(fn($values, $attr) => $attr . '=' . $values->implode(','))
            ->implode(';');

        // Якщо передали не по правильному - 404
        if ($slug !== $sortFilters) {
            \abort(404);
        }

        $attributeProperties = Cache::remember(md5(serialize('attributes_with_properties')), 3600, function () {
            return Attribute::query()
                ->with('properties:id,attribute_id,slug')
                ->get()
                ->mapWithKeys(fn($attr) => [
                    $attr->slug => $attr->properties->pluck('slug')->toArray()
                ]);
        });

        $isValid = $filters->every(function ($propertiesSlugs, $attrSlug) use ($attributeProperties) {
            return isset($attributeProperties[$attrSlug])
                && $propertiesSlugs->every(fn($prop) => in_array($prop, $attributeProperties[$attrSlug], true));
        });

        if (!$isValid) {
            \abort(404);
        }
    }

    /**
     * @api {get} /api/app/translations 03. Переклади інтерфейсу
     * @apiVersion 1.0.0
     * @apiName AppTranslation
     * @apiGroup App
     *
     * @apiHeader {String} sLocale Код локалізації: `sLocale: uk`
     *
     * @apiDescription Отримати переклади інтерфейсу користувача.
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "aboutProductTitle": "Про товар",
     *          "aboutUs": "Про нас",
     *          "addAReview": "Додати відгук",
     *      }
     *  }
     */
    public function translations()
    {
        $data = Cache::remember(Translation::getCacheName(), 3600, function () {
            $translations = Translation::with('translation')->orderBy('key')->get();

            return $translations->pluck('value', 'key')->toArray();
        });

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * @api {get} /api/app/menu/catalog 04. Отримати меню Каталог
     * @apiVersion 1.0.0
     * @apiName AppMenuCatalog
     * @apiGroup App
     *
     * @apiHeader {String=en,uk,ru,de,...} sLocale Локаль
     *
     * @apiDescription Отримуємо меню у вигляді дерева категорій товарів (з кількістю товарів по категоріям, зображеннями категорій).
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     * {
     *   "data": [
     *     {
     *       "id": "0dcc3390-938c-432b-82a7-98e1ed39f14c",
     *       "name": "Жінкам",
     *       "path": null,
     *       "target": null,
     *       "image": {
     *         "id": "9e395126-0bc6-4f27-a693-4678cdd620cb",
     *         "name": "norm-db",
     *         "url": "http://dropshop.test/storage/9e395126-0bc6-4f27-a693-4678cdd620cb/norm-db.png"
     *         }
     *       },
     *       "children": [
     *         {
     *           "id": "58a23bcb-4a9c-4e89-9b97-a57d57e5cb99",
     *           "name": "Жіночі вишиванки",
     *           "path": null,
     *           "target": null,
     *           "image": null,
     *           "children": [],
     *           "model": {
     *             "id": "9310866f-d39a-4a61-b80e-a95d82512c95",
     *             "slug": "zinochi-vyshyvanky",
     *             "name": "Жіночі вишиванки",
     *             "variations_count": 6,
     *             "entity": "product_categories",
     *             "image": null
     *           }
     *         },
     *         {
     *           "id": "af318b19-f24e-431d-80fb-883ae7b9aa2d",
     *           "name": "Вишиті сукні",
     *           "path": null,
     *           "target": null,
     *           "image": null,
     *           "children": [],
     *           "model": {
     *             "id": "f4683342-8403-4ae1-b7f3-398dd98b1f76",
     *             "slug": "vyshyti-sukni",
     *             "name": "Вишиті сукні",
     *             "variations_count": 0,
     *             "entity": "product_categories",
     *             "image": null
     *           }
     *         },
     *         {
     *           "id": "da6bb8b4-ce6e-493e-8436-c38230b5773a",
     *           "name": "Світшоти",
     *           "path": null,
     *           "target": null,
     *           "image": null,
     *           "children": [],
     *           "model": {
     *             "id": "8a32efcd-2841-4194-b01e-669060434ae4",
     *             "slug": "svitshoty",
     *             "name": "Світшоти",
     *             "variations_count": 0,
     *             "entity": "product_categories",
     *             "image": null
     *           }
     *         }
     *       ],
     *       "model": {
     *         "id": "74b8fc37-97dd-42ff-8a8c-7efaafcb1b8e",
     *         "slug": "zinkam",
     *         "name": "Жінкам",
     *         "variations_count": 8,
     *         "entity": "product_categories",
     *         "image": {
     *           "id": "d43513bc-c030-41bf-be90-8b7ddd3d1d0c",
     *           "name": "norm-db",
     *           "url": "http://dropshop.test/storage/d43513bc-c030-41bf-be90-8b7ddd3d1d0c/norm-db.png"
     *         }
     *       }
     *     }
     *   ]
     * }
     */
    public function menuCatalog()
    {
        $menuItems = Menuitem::query()
            ->with('translations', 'model.translations', 'children', 'media', 'model.domain', 'model.media.model.domain')
            ->whereMenu('catalog')
            ->get()->toTree();

        MenuitemResource::isCatalog();
        return MenuitemResource::collection($menuItems);
    }
}
