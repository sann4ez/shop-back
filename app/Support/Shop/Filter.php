<?php

namespace App\Support\Shop;

use App\Http\Client\Api\Resources\AttributeResource;
use App\Http\Client\Api\Resources\TermSimpleResource;
use App\Models\Attribute;
use App\Models\ProductVariation;
use App\Models\Term;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

final class Filter
{
    public function facet(array $attrs = [], array $options = [])
    {
        if ($category = Arr::get($attrs, 'category')) {
            $term = Term::whereSlug($category)
                ->with('descendants')
                ->firstOrFail();

            $ids = $term->descendants->pluck('id')->toArray();
            $ids[] = $term->id;

            $attributes = Term::whereIn('id', $ids)
                ->with(['attrs.properties.media'])
                ->get()
                ->pluck('attrs')
                ->flatten()
                ->unique('slug')
                ->where('in_filter', true)
                ->sortBy('weight');
        } else {
            $attributes = Attribute::with(['properties.media'])
                ->where('in_filter', true)
                ->latest('weight')
                ->get();
        }

        $searchedProductVariations = ProductVariation::with('properties:id', 'product:id')
            ->filterable($attrs)
            ->get();

        if ($q = request('q')) {
            $searchedProductVariations = ProductVariation::with('properties:id', 'product:id')
                ->filterable(['q' => $q])
                ->get();
        }

        // Головна категорія
        $categoryMain = Term::byVocabulary(Term::VOCABULARY_PRODUCT_CATEGORIES)
            ->where('slug', $category)
            ->first();

        // Дочірні категорії
        $categoriesChild = $categoryMain?->getDescendants();

        // Об’єднані категорії
        $mergedCategories = collect([$categoryMain])->merge($categoriesChild);

        // Варіації товарів для категорій
        $variationsByCategories = ProductVariation::with('properties:id', 'product:id,brand_id', 'product.properties:id')
            ->filterable([
                'facet' => [
                    'categories' => $mergedCategories->pluck('slug')->toArray()
                ]
            ])
            ->byGropedType()
            ->get();

        // Дозволені властивості
        $variationAllowedProperties = $variationsByCategories->pluck('properties')->flatten()->unique('id');
        $productAllowedProperties = $variationsByCategories->pluck('product')->pluck('properties')->flatten()->unique('id');
        $allowedProperties = $variationAllowedProperties->merge($productAllowedProperties)->unique('id');

        // Категорії
        $categories = Term::byVocabulary(Term::VOCABULARY_PRODUCT_CATEGORIES)
            ->get()
            ->toTree();

        foreach ($attributes as $attribute) {
            $isAllowed = false;
            foreach ($attribute->properties as $property) {
                $property->is_allowed = $allowedProperties->contains('id', $property->id);
                if ($property->is_allowed) {
                    $isAllowed = true;
                }
            }
            $attribute->is_allowed = $isAllowed;
        }

        $prices = [
            'min' => floor(ProductVariation::min('price')),
            'max' => ceil(ProductVariation::max('price')),
            'category' => [
                'from' => floor($variationsByCategories->min('price')),
                'to' => ceil($variationsByCategories->max('price')),
            ],
            'search' => [
                'from' => floor($searchedProductVariations->min('price')),
                'to' => ceil($searchedProductVariations->max('price')),
            ]
        ];

        $res = [
            'categories' => TermSimpleResource::collection($categories),
            'attributes' => AttributeResource::collection($attributes),
            'prices' => $prices,
            'info' => [
                'in_stock_variations_count' => ProductVariation::query()
                    ->byAllowed()
                    ->where('stock_qty', '>', 0)
                    ->count(),
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
}
