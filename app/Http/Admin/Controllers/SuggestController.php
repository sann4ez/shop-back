<?php

namespace App\Http\Admin\Controllers;

use App\Models\User;
//use App\Models\Extern\Comment;
use App\Models\Page;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Term;
use App\Support\Shippings\Ukrposhta\Ukrposhta;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
//use App\Models\Post;

final class SuggestController
{
    public function terms(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string',
            'limit' => 'nullable|integer|between:0,255',
            'vocabulary' => ['sometimes', Rule::in(Term::vocabulariesList('slug'))],
            'ancestor_id' => 'sometimes|nullable|string',
        ]);

        $terms = Term::query()
            ->searchByVocabulary(
            $request->vocabulary,
            $request->q,
            $request->limit,
            $request->ancestor_id,
            null,
            $request->sLocale,
        )->get();

        $format = $request->get('format');

        if ($format === 'treeselect') {
            return response()->json([
                'result' => $terms->toTree()->toArray(),
                'selected' => $request->selected,
            ]);
        }

        return response()->json([
            'results' => $terms->map(function ($tag) use ($format) {
                return [
                    'id' => $format === 'name' ? $tag->name : $tag->id,
                    'text' => $tag->name,
                ];
            })
        ]);
    }

    public function googleCategories(Request $request)
    {
        $merchantCategories = (new GoogleMerchant())->getCategories($request->only('q', 'limit'));

        $results = array_map(function($item) {
            return [
                'id' => $item['id'],
                'text' => $item['name']
            ];
        }, $merchantCategories);

        // Додаємо пустий запис на початок, щоб можна було відключити категорію (empty_value не працює)
        array_unshift($results, [
            'id' => '',
            'text' => '--'
        ]);

        return response()->json([
            'results' => $results,
        ]);
    }

    public function rozetkaCategories(Request $request)
    {
        if (empty($request->q)) {
            return response()->json(['results' => []]);
        }

        $url = 'https://api-seller.rozetka.com.ua/market-categories/search';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Language' => 'uk',
            'Authorization' => "Bearer {token}", // TODO get rozetka token
        ])->get($url, [
            'name' => $request->input('q')
        ]);

        if ((!$response->successful()) || (!$response['success'] ?? false)) {
            OperationResult::warning('Rozetka categories: ' . $response->body());
            return [];
        }

        $rozetkaCategories = $response['content']['marketCategorys']; // TODO check

        return response()->json([
            'results' => array_map(function($item) {
                return [
                    "id" => $item["id"],
                    "text" => $item["name"]
                ];
            }, $rozetkaCategories),
        ]);
    }

    public function products(Request $request)
    {
        if (empty($request->q)) {
            return response()->json(['results' => []]);
        }

        $products = Product::with('translations', 'variation.translations')
            ->when($request->type, fn ($b) => $b->where('type', $request->type))
            ->when($request->except, fn ($b) => $b->whereNotIn('id', Arr::wrap($request->except)))
            ->when($q = $request->q, fn ($b) => $b->whereTranslationLike('name', "%$q%", \Domain::getLocale()))
            ->limit(15)->get();

        return response()->json([
            'results' => $products->map(function ($p) {
                return [
                    //'text' => $p->name . ' | ' . $p->variation->getName(),
                    'text' => $p->name,// . ' | ' . $p->variation->getTitleName(),
                    'id' => $p->id,
                ];
            }),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function productVariations(Request $request)
    {
        if (empty($request->q)) {
            return response()->json(['results' => []]);
        }

        $productVariations = ProductVariation::with(['product'])
            ->when($request->except, fn ($b) => $b->whereNotIn('id', Arr::wrap($request->except)))
            ->filterable($request->all(), $request->only('locale'))
            ->limit(15)->get()->map(fn(ProductVariation $v) => [
                'id' => $v->id,
                'text' => $v->getTitleName(),
                'name' => $v->getName(),
                'price' => $v->price,
                'stock_qty' => ($request->warehouse_id ? $v->wareoffers->first()?->qty : $v->stock_qty) ?: 0,
                'price_cost' => $v->price_cost,
                'sku' => $v->sku,
            ]);

        return response()->json([
            'results' => $productVariations->toArray(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function users(Request $request)
    {
        if (empty($request->q)) {
            return response()->json(['results' => []]);
        }

        $users = User::when($q = $request->q, function ($b) use ($q) {
                $b->where('name', 'LIKE', "%$q%")
                    ->orWhere('email', 'LIKE', "%$q%")
                    ->orWhere('phone', 'LIKE', "%$q%");
            })->byNotDev()->select('id', 'name', 'email', 'phone')
            ->limit(15)->get();

        return response()->json([
            'results' => $users->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->getTitleStr(),
            ]),
        ]);
    }

    public function posts(Request $request)
    {
        if (empty($request->q)) {
            return response()->json(['results' => []]);
        }

        $users = Post::with('translations')->filterable()->select('id', 'name')
            ->limit(15)->get();

        return response()->json([
            'results' => $users->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->name,
            ]),
        ]);
    }
    public function pages(Request $request)
    {
        if (empty($q = $request->q)) {
            return response()->json(['results' => []]);
        }
        $pages = Page::with('translations')->whereTranslationLike('name', 'LIKE', "%{$q}%")->select('id', 'name')
            ->limit(15)->get();

        return response()->json([
            'results' => $pages->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->name,
            ]),
        ]);
    }

    public function comments(Request $request)
    {
        if (empty($q = $request->q)) {
            return response()->json(['results' => []]);
        }
        $comments = Comment::where('body', 'LIKE', "%{$q}%")->select('id', 'body')->with('user')
            ->whereNull('parent_id')
            ->limit(15)->get();

        return response()->json([
            'results' => $comments->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->getTitle(),
            ]),
        ]);
    }

    public function orders(Request $request)
    {
        if (empty($q = $request->q)) {
            return response()->json(['results' => []]);
        }
        $orders = Order::with('user')
            ->where('number', 'LIKE', "%{$q}%")
            ->orWhereHas('user', function ($b) use ($q) {
                $b->where('name', 'LIKE', "%$q%")
                    ->orWhere('email', 'LIKE', "%$q%")
                    ->orWhere('phone', 'LIKE', "%$q%")
                    ->byNotDev();
            })
            ->where('type', Order::TYPE_ORDER)
            ->whereIn('perform', [Order::PERFORM_CONFIRMED, Order::PERFORM_DONE])
            ->limit(15)->get();

        return response()->json([
            'results' => $orders->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->getTitleStr(),
            ]),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function promotions(Request $request)
    {
        if (empty($q = $request->q)) {
            return response()->json(['results' => []]);
        }
        $promotions = Promotion::with('translations')->whereTranslationLike('name', "%{$q}%")->select('id', 'name')
            ->limit(15)->get();

        return response()->json([
            'results' => $promotions->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->name,
            ]),
        ]);
    }

    /**
     * Укрпошта - області
     *
     * @param Request $request
     * @return array|bool
     */
    public function regionsUkrposhta(Request $request): array|bool
    {
        /** @var Ukrposhta $ukApi */
        $ukApi = app(Ukrposhta::class);

        $regionName = $request->term;
        $regions = $ukApi->getRegionsUkrposhta($regionName);

        $regions_data = array_map(function ($region) {
            return [
                'id' => $region['REGION_ID'],
                'text' => $region['REGION_UA'],
            ];
        }, $regions['Entry'] ?? []);

        return $regions_data ?: false;
    }

    /**
     * Укрпошта - міста
     *
     * @param Request $request
     * @return array|bool
     */
    public function citiesUkrposhta(Request $request): array|bool
    {
        /** @var Ukrposhta $ukApi */
        $ukApi = app(Ukrposhta::class);

        $regionId = $request->region_id;
        $cityName = $request->term;
        $cities = $ukApi->getCitiesUkrposhta($regionId, $cityName);

        $cities_data = array_map(function ($city) {
            return [
                'id' => $city['CITY_ID'],
                'text' => $city['CITY_UA'],
            ];
        }, $cities['Entry'] ?? []);

        return $cities_data ?: false;
    }

    /**
     * Укрпошта - відділення
     *
     * @param Request $request
     * @return array|bool
     */
    public function departmentsUkrposhta(Request $request): array|bool
    {
        /** @var Ukrposhta $ukApi */
        $ukApi = app(Ukrposhta::class);

        $cityId = $request->city_id;
        $departments = $ukApi->getDepartmentUkrposhta($cityId);

        $departments_data = array_map(function ($department) {
            $text = ($department['TYPE_ACRONYM'] == 'МВ') ? ($department['POSTCODE'] . ', ' . $department['STREET_UA_VPZ']) : ($department['POSTCODE'] . ', ' . $department['CITY_UA'] . ', ' . $department['TYPE_LONG']);
            return [
                'id' => $department['POSTOFFICE_ID'],
                'text' => $text,
                'postcode' => $department['POSTCODE'],
                'acronym' => $department['TYPE_ACRONYM'],
            ];
        }, $departments['Entry'] ?? []);

        return $departments_data ?: false;
    }
}
