<?php

namespace App\Support\Shop;

use App\Http\Client\Api\Resources\Eav\AttributeResource;
use App\Http\Client\Api\Resources\Shop\ItemMarkerListResource;
use App\Http\Client\Api\Resources\Terms\TermSimpleResource;
use App\Models\Eav\Attribute;
use App\Models\Item;
use App\Models\Shop\ProductVariation;
use App\Models\Term;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

final class Filter
{
    public function facet(array $attrs = [], array $options = [])
    {
        if ($category = Arr::get($attrs, 'category')) {
            /** @var Term $term */
            $term = Term::whereSlug($category)->with('descendants')->firstOrFail();

            /** @var array $ids */
            $ids = $term->descendants->pluck('id')->toArray();
            $ids[] = $term->id;

            /** @var Collection $attributes */
            $attributes = Term::whereIn('id', $ids)
                ->withTrans(['attrs.translations', 'attrs.properties.translations', 'attrs.properties.media'])
                ->get()->pluck('attrs')->flatten()->unique('slug')
                ->where('in_filter', true)
                ->sortBy('weight');
        } else {
            /** @var Collection $attributes */
            $attributes = Attribute::withTrans(['properties.translations', 'properties.media'])
                ->where('in_filter', true)
                ->latest('weight')->get();
        }

        /** @var Collection $variationAllowedProperties */
        $searchedProductVariations = ProductVariation::with('properties:id', 'product:id,brand_id',/*'product.brand:id',*/ 'product.properties:id')
            ->filterable($attrs)->get();

        if ($q = request('q')) {
            $searchedProductVariations = ProductVariation::with('properties:id', 'product:id,brand_id',/*'product.brand:id',*/ 'product.properties:id')
                ->filterable([
                    'q' => $q,
                ])->get();
        }

        // Головна категорія
        $categoryMain = Term::byVocabulary(Term::VOCABULARY_PRODUCT_CATEGORIES)
            ->where('slug', $category)
            ->first();

        // Дочірні категорії
        $categoriesChild = $categoryMain?->getDescendants();

        // Головна і дочірні категорії
        $mergedCategories = collect([$categoryMain])->merge($categoriesChild);

        // Всі варіації в головній і дочірній категоріях
        $variationsByCategories = ProductVariation::with('properties:id', 'product:id,brand_id',/*'product.brand:id',*/ 'product.properties:id', 'product.markers')
            ->filterable([
                'facet' => [
                    'categories' => $mergedCategories->pluck('slug')->toArray()
                ]
            ])
            ->byGropedType()
            ->get();

        // Доступні для вибору значення атрибутів
        $variationAllowedProperties = $variationsByCategories->pluck('properties')->flatten()->unique('id');
        $productAllowedProperties = $variationsByCategories->pluck('product')->pluck('properties')->flatten()->unique('id');
        $allowedProperties = $variationAllowedProperties->merge($productAllowedProperties)->unique('id');

        // Категорії товарів
        $categories = Term::withTrans()
            ->byVocabulary(Term::VOCABULARY_PRODUCT_CATEGORIES)
            ->where('status', Term::STATUS_PUBLISHED)
            ->get()
            ->toTree();

        // Всі бренди товарів
        $brands = Term::withTrans()
            ->byVocabulary(Term::VOCABULARY_BRANDS)
            ->where('status', Term::STATUS_PUBLISHED)
            /*->with('media')*/
            ->get();

        // Всі маркери товарів
        $markers = Item::withTrans()->where('type', Item::TYPE_PRODUCT_MARKER)->get();

        if (config('services.elasticsearch.active')) {
            $aggregation = $this->getAggregation($attrs, [
                'categories' => TermSimpleResource::collection($categories),
                'brands' => TermSimpleResource::collection($brands),
                'markers' => ItemMarkerListResource::collection($markers),
                'attributes' => AttributeResource::collection($attributes),
            ]);
        }

        // Доступні для вибору маркери товарів
        /** @var Collection $allowedMarkers */
        $allowedMarkers = collect();

        foreach ($variationsByCategories as $variation) {
            $allowedMarkers = $allowedMarkers->merge($variation->getMarkers());
        }

        $allowedMarkers = $allowedMarkers->unique('id')->values();

        if (config('services.elasticsearch.active')) {
            $aggregationMarkers = collect(Arr::get($aggregation, 'markers', []));
        }

        foreach ($markers as $marker) {
            $marker->is_allowed = $allowedMarkers->contains('id', $marker->id);

            if (config('services.elasticsearch.active')) {
                $found = false;
                if ($aggregationMarkers->contains('key', $marker->id)) {
                    $marker->aggregation = $aggregationMarkers->firstWhere('key', $marker->id)['doc_count'];
                    $found = true;
                }

                if (!$found) {
                    $marker->aggregation = \FacetFilter::has('markers')
                        ? $aggregation['total_filtered']
                        : 0;
                }
            }
        }

        foreach ($attributes as $attribute) {
            $isAllowed = false;
            foreach ($attribute->properties as $property) {
                $property->is_allowed = $allowedProperties->contains('id', $property->id);
                if ($property->is_allowed) {
                    $isAllowed = true;
                }

                if (config('services.elasticsearch.active')) {
                    $aggregationAttributes = collect(Arr::get($aggregation, $attribute->slug, []));
                    $found = false;
                    if ($aggregationAttributes->contains('key', $property->id)) {
                        $property->aggregation = $aggregationAttributes->firstWhere('key', $property->id)['doc_count'];
                        $found = true;
                    }

                    if (!$found) {
                        $property->aggregation = \FacetFilter::has('brands')
                            ? $aggregation['total_filtered']
                            : 0;
                    }
                }
            }
            $attribute->is_allowed = $isAllowed;
        }

        // Доступні для вибору бренди товарів
        /** @var Collection $allowedBrands */
        $allowedBrands = $variationsByCategories->pluck('product')->unique('brand_id');
        if (config('services.elasticsearch.active')) {
            $aggregationBrands = collect(Arr::get($aggregation, 'brands', []));
        }
        foreach ($brands as $brand) {
            $brand->is_allowed = $allowedBrands->contains('brand_id', $brand->id);

            if (config('services.elasticsearch.active')) {
                $found = false;
                if ($aggregationBrands->contains('key', $brand->id)) {
                    $brand->aggregation = $aggregationBrands->firstWhere('key', $brand->id)['doc_count'];
                    $found = true;
                }

                if (!$found) {
                    $brand->aggregation = \FacetFilter::has('brands')
                        ? $aggregation['total_filtered']
                        : 0;
                }
            }
        }

        $prices = [
            // Беруться всі товари
            'min' => floor(ProductVariation::min('price')),
            'max' => ceil(ProductVariation::max('price')),
            // Товари які видно
//            'from' => floor($productVariations->min('price')),
//            'to' => ceil($productVariations->max('price')),
            // При вибору категорій
            'category' => [
                'from' => floor($variationsByCategories->min('price')),
                'to' => ceil($variationsByCategories->max('price')),
            ],
            // При пошуку
            'search' => [
                'from' => floor($searchedProductVariations->min('price')),
                'to' => ceil($searchedProductVariations->max('price')),
            ]
        ];

        $res = [
            'categories' => TermSimpleResource::collection($categories),
            'brands' => TermSimpleResource::collection($brands),
            'markers' => ItemMarkerListResource::collection($markers),
            'attributes' => AttributeResource::collection($attributes),
            // 'allowedProperties' => PropertyResource::collection($allowedProperties),
            'prices' => $prices,
            'info' => [
                // товарі наявні/доступні
                'in_stock_variations_count' => ProductVariation::query()
                    ->byAllowed()
                    ->where('stock_qty', '>', 0)->count(),
            ],
        ];

        if (!empty($options['only'])) {
            $res = Arr::only($res, Arr::wrap($options['only']));
        }

        if (!empty($options['except'])) {
            $res = Arr::except($res, Arr::wrap($options['except']));
        }

        return $res;
    }

    protected function getAggregation(array $attrs, $facet = []): array
    {
        $params = \FacetFilter::toArray(Arr::get($attrs, \FacetFilter::getFilterUrlKey())) + [
                'only_default_variation' => \Domain::getOpt('variations.only_default_variation'),
            ];

        $filterForAggregation = [
            'only_default_variation' => \Domain::getOpt('variations.only_default_variation'),
        ];

        if (isset($attrs['category'])) {
            $filterForAggregation = array_merge($filterForAggregation, [
                'category' => [Term::where('slug', $attrs['category'])->first()->id],
            ]);
            $params = array_merge($params, [
                'category' => [Term::where('slug', $attrs['category'])->first()->id],
            ]);
        }

        if (isset($attrs['q'])) {
            $filterForAggregation = array_merge($filterForAggregation, [
                'q' => $attrs['q'],
            ]);
            $params = array_merge($params, [
                'q' => $attrs['q'],
            ]);
        }

        return (new ProductVariation())->getAggregation($params, $filterForAggregation, $facet);
    }
}
