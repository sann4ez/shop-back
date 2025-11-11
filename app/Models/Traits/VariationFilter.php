<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait VariationFilter
{
    public function scopeFilterable(Builder $query, array $params = [], array $default = []): Builder
    {
        $params = array_filter($params);

        // --- Основні фільтри ---
        $query
            ->when(isset($params['status']), fn($q) =>
            $q->where('status', $params['status'])
            )
            ->when(isset($params['in_stock']) && $params['in_stock'], fn($q) =>
            $q->where('stock_qty', '>', 0)
            )
            ->when(isset($params['price_from']), fn($q) =>
            $q->where('price', '>=', $params['price_from'])
            )
            ->when(isset($params['price_to']), fn($q) =>
            $q->where('price', '<=', $params['price_to'])
            )
            ->when(isset($params['has_discount']) && $params['has_discount'], fn($q) =>
            $q->whereNotNull('old_price')->whereColumn('old_price', '>', 'price')
            )
            ->when(isset($params['has_promotion']) && $params['has_promotion'], fn($q) =>
            $q->whereNotNull('promotion_id')
            );

        // --- Зв’язки ---
        $query
            ->when(isset($params['category_ids']), fn($q) =>
            $q->whereHas('categories', fn($q2) => $q2->whereIn('categories.id', (array) $params['category_ids']))
            )
            ->when(isset($params['brand_ids']), fn($q) =>
            $q->whereHas('brands', fn($q2) => $q2->whereIn('brands.id', (array) $params['brand_ids']))
            )
            ->when(isset($params['tag_ids']), fn($q) =>
            $q->whereHas('tags', fn($q2) => $q2->whereIn('tags.id', (array) $params['tag_ids']))
            )
            ->when(isset($params['marker_ids']), fn($q) =>
            $q->whereHas('markers', fn($q2) => $q2->whereIn('markers.id', (array) $params['marker_ids']))
            );

        // --- Пошук за назвою ---
        $query->when(isset($params['q']), function ($q) use ($params) {
            $term = trim($params['q']);
            $q->where('name', 'like', "%{$term}%");
        });

        // --- Сортування ---
        $query->when(isset($params['sort']), function ($q) use ($params, $default) {
            $sort = $params['sort'];
            $direction = $params['direction'] ?? 'asc';

            return match ($sort) {
                'price'      => $q->orderBy('price', $direction),
                'name'       => $q->orderBy('name', $direction),
                'stock_qty'  => $q->orderBy('stock_qty', $direction),
                'created_at' => $q->orderBy('created_at', $direction),
                default      => $q->orderBy($default['sort'] ?? 'created_at', $default['direction'] ?? 'desc'),
            };
        }, function ($q) use ($default) {
            $q->orderBy($default['sort'] ?? 'created_at', $default['direction'] ?? 'desc');
        });

        return $query;
    }
}
