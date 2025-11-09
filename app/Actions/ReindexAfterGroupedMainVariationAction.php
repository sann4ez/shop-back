<?php

namespace App\Actions;

use App\Models\ProductVariation;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Додаємо дані в індекс основної варіації від ін. варіацій з групи основної варіації.
 * Для пошуку, фільтрування в каталозі.
 */
class ReindexAfterGroupedMainVariationAction
{
    use AsAction;

    public function handle(ProductVariation $variation)
    {
        $gropedType = \Domain::getOpt('variations.groped_type');
        if (!in_array($gropedType, [ProductVariation::GROUPING_TYPE_ATTRIBUTE, ProductVariation::GROUPING_TYPE_SINGLE, ProductVariation::GROUPING_TYPE_MAIN])) {
            return false;
        }

        //Log::info('0', [$variation->id, $variation->sku]);
        if ($variation->is_attribute_groped) {
            //Log::info(1 ,$variation->index['facet'] ?? []);
            $facet = [];
            $elasticName = $variation->getName();
            $elasticSku = $variation->getSku();
            /** @var ProductVariation $variation */
            $prVariations = $variation->product->variations->where('id', '<>', $variation->id)->where('grouped_id', $variation->grouped_id)->where('status', ProductVariation::STATUS_PUBLISHED);
            foreach ($prVariations as $prVariation) {
                $ids = $prVariation->getAttributesPropertiesListArray('id', 'id');
                $facet = array_merge_recursive($facet, $ids);
                $elasticName = $elasticName . ' ' . $prVariation->getName();
                $elasticSku = $elasticSku . ' ' . $prVariation->getSku();
            }

            $variation->setAttribute('index->facet', array_merge_recursive_strategy($variation->index['facet'] ?? [], $facet))->saveQuietly();
            //Log::info(2 ,$variation->index['facet'] ?? []);

            if (config('services.elasticsearch.active')) {
                $variation->updateDocument([
                    'name' => $elasticName,
                    'sku' => $elasticSku,
                    'price_min' => $prVariations->min('price') ?: 0,
                    'price_max' => $prVariations->max('price') ?: 0,
                    'properties' => array_merge_recursive_strategy($variation->index['facet'] ?? [], $facet),
                ]);
            }
        }
    }
}
