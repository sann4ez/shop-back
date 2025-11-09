<?php

namespace App\Actions;

use App\Events\VariationSaved;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

final class StoreVariationAction
{
    use AsAction;

    public function handle(Product $product, array $data): ProductVariation
    {
        $data['status'] = Arr::get($data, 'status', ProductVariation::STATUS_PUBLISHED);
        $data['income_at'] = Arr::get($data, 'income_at', $product->income_at);

        if ($fields = Arr::get($data, 'fields') ?: Arr::get($data, 'variation.fields')) {
            $data['fields'] = ProductVariation::prepareFieldsForSave($fields, Arr::get($data, 'notarrays'));
        }

        /** @var ProductVariation $variation */
        $variation = $product->variations()->create(Arr::only($data, [
            'name', 'sku', 'sku_extern', 'barcode', 'barcodes', 'slug', 'price', 'price_old',
            'stock_qty', 'limit_qty', 'price_cost', 'min_qty', 'multiplicity',
            'body', 'status', 'added', 'fields', 'income_at', 'rozetka_id', 'rozetka_send', 'product_id',
        ]));

        $variation->properties()
            ->sync(array_filter(\Arr::flatten(Arr::get($data, 'properties', []))));

        // TODO
        $variation->reUpdateSlug(Arr::get($data, 'slug'));

        if (!$variation->sku) {
            $variation->setAttribute('sku', $variation->slug);
        }

        $variation->save();

        VariationSaved::dispatch($variation);

        if (!is_null($product->category)) {
            CategoryVariationsCount::dispatch($product->category);
        }

        return $variation;
    }
}
