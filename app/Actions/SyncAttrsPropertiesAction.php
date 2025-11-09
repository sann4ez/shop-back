<?php

namespace App\Actions;

use App\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * TODO: Deprecated!
 */
final class SyncAttrsPropertiesAction
{
    use AsAction;

    public function handle(Product $product, array $data = []): array
    {
        $res = $product->properties()->sync(array_filter(\Arr::flatten($data)));

//        ProductSaved::dispatch($product);

        return $res;
    }
}
