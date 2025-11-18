<?php

namespace App\Models\Traits;

use App\Models\Attribute;
use App\Models\Property;
use App\Models\Term;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait VariationFilter
{
    /**
     * Фільтрація/пошук/сортування по локальному індексу.
     *
     * @param Builder $builder
     * @param array $params
     * @param array $default
     * @return void
     */
    public function scopeFilterable(Builder $builder, array $params = [], array $default = [])
    {
        $params = ($params ?: request()->all()) + $default;
        $f = self::prepeareFilterArray($params);
        $locale = Arr::get($f, 'locale');

        // Статус варіації & товара
        $builder->when($val = Arr::get($f, 'status'), fn($q) => $q->whereJsonContains("index->status", $val));

        // Пошук по тексту (SKU або ID)
        $search = !empty($f['q']) ? mb_strtolower($f['q']) : null;
        $builder->when($search, fn($q) => $q->where("index->q", 'LIKE', "%{$search}%")->orWhere("index->id", 'LIKE', "%{$search}%"));

        // Ціна
        $pFrom = $f['price']['from'] ?? $f['price_from'] ?? null;
        $builder->when($pFrom !== null, fn($q) => $q->where('price', '>=', $pFrom));

        $pTo = $f['price']['to'] ?? $f['price_to'] ?? null;
        $builder->when($pTo !== null, fn($q) => $q->where('price', '<=', $pTo));

        // Наявність на складі
        $builder->when(!is_null($val = Arr::get($f, 'stock_qty_from')), fn($q) => $q->where('stock_qty', '>=', $val));
        $builder->when(!is_null($val = Arr::get($f, 'stock_qty_to')), fn($q) => $q->where('stock_qty', '<=', $val));

        // Парні та основні
        $builder->when(!is_null($val = Arr::get($f, 'has_parities')), fn($q) => $q->where('index->has_parities', $val ? 1 : 0));
        $builder->when(!is_null($val = Arr::get($f, 'is_default')), fn($q) => $q->where('is_default', $val ? 1 : 0));

        // Групування Variation's
        if (isset($f['groped_type'])) {
            $builder->byGropedType($f['groped_type']);
        }

        // Фільтри по SKU / IDs / without
        $builder->when($val = filter_explode(Arr::get($f, 'sku')), fn($q) => $q->whereIn('sku', $val));
        $builder->when($val = filter_explode(Arr::get($f, 'ids')), fn($q) => $q->whereIn('id', $val));
        $builder->when($val = filter_explode(Arr::get($f, 'without')), fn($q) => $q->whereNotIn('id', $val));

        // Фільтри по атрибутах
        foreach (Arr::get($f, 'facet', []) as $key => $value) {
            $builder->where(function ($builder2) use ($key, $value) {
                if ($values = Arr::wrap($value)) {
                    foreach ($values as $val2) {
                        $builder2->orWhereJsonContains("index->facet->{$key}", $val2);
                    }
                }
            });
        }

        // Сортування
        $order = in_array(Arr::get($f, 'order', 'asc'), ['asc', 'desc']) ? Arr::get($f, 'order', 'asc') : 'asc';
        $sort = Arr::get($f, 'sort');

        if ($sort) {
            if (in_array($sort, ['price', 'price_cost', 'stock_qty'])) {
                $builder->orderBy($sort, $order);
            } elseif ($sort === 'random') {
                $builder->inRandomOrder();
            } else {
                $builder->latest($sort);
            }
        } else {
            $builder->latest('income_at');
        }

        $builder->latest('created_at');
        $builder->when($val = Arr::get($f, 'limit'), fn($q) => $q->limit($val));
    }

    public static function prepeareFilterArray(array $data = [])
    {
        $attrs = array_merge($data, Arr::only($data, ['q', 'sort', 'order', 'category', 'f']));
        $attrs['locale'] = app()->getLocale();

        $facet = [];
        $terms = Term::all()->pluck('id', 'slug')->toArray();
        $attributes = Attribute::all()->pluck('id', 'slug')->toArray();
        $properties = Property::all()->pluck('id', 'slug')->toArray();

        $facets = Arr::get($attrs, 'facet', []);
        if (is_array($facets)) {
            foreach ($facets as $key => $val) {
                if (in_array($key, array_keys($attributes))) {
                    if ($val = Arr::wrap($val)) {
                        $facet[$attributes[$key]] = array_values(Arr::only($properties, array_filter($val)));
                    }
                }
            }
        }

        if (Arr::get($attrs, 'category') && is_string(Arr::get($attrs, 'category'))) {
            $categoryId = Arr::get($terms, $attrs['category']);
            $attrs['categories'] = array_unique(array_merge(Arr::get($attrs, 'categories', []), [$categoryId]));
        }

        $attrs['facet'] = $facet;

        return $attrs;
    }
}
