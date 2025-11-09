<?php

namespace App\Actions;

use App\Events\VariationSaved;
use App\Models\ProductVariation;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

final class UpdateVariationAction
{
    use AsAction;

    public function handle(ProductVariation $variation, array $data): ProductVariation
    {
        $data['status'] = Arr::get($data, 'status', ProductVariation::STATUS_PUBLISHED);
        $data['income_at'] = Arr::get($data, 'income_at', $variation->product->income_at);

        if ($fields = Arr::get($data, 'fields') ?: Arr::get($data, 'variation.fields')) {
            $data['fields'] = ProductVariation::prepareFieldsForSave($fields, Arr::get($data, 'notarrays'));
        }

        $variation->properties()
            ->sync(array_filter(\Arr::flatten(Arr::get($data, 'properties', []))));

        $variation->update(Arr::only($data, [
            'name', 'sku', 'sku_extern', 'barcode', 'barcodes', 'slug', 'price', 'price_old',
            'stock_qty', 'limit_qty', 'price_cost', 'min_qty', 'multiplicity',
            'body', 'added', 'status', 'fields', 'income_at', 'rozetka_id', 'rozetka_send', 'product_id',
        ]));

//        $variation->setAttribute('sku', $variation->sku .'_'. $variation->slug);
//        $variation->save();

        VariationSaved::dispatch($variation);

        return $variation;
    }
}
