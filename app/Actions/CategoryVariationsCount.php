<?php

namespace App\Actions;

use App\Models\ProductVariation;
use App\Models\Term;
use Lorisleiva\Actions\Concerns\AsAction;

class CategoryVariationsCount
{
    use AsAction;

    public function handle(Term $term)
    {
        $term->update([
            'variations_count' => $this->getVariationsCount($term),
        ]);

        foreach ($term->getAncestors() as $parentTerm) {
            $parentTerm->update([
                'variations_count' => $this->getVariationsCount($parentTerm),
            ]);
        }
    }

    public function getVariationsCount(Term $term)
    {
        // Нащадки категорії
        $categorySlugs = $term->getDescendants()->pluck('slug')->toArray();
        $categorySlugs[] = $term->slug;
        $filter = ['facet' => ['categories' => $categorySlugs]];

        // Варіації (по категорії і її предках)
        $variationsCount = ProductVariation::with('product.category')->filterable($filter)
            ->byGropedType()
            ->count();

        return $variationsCount;
    }
}
