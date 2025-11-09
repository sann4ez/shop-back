<?php

namespace App\Actions;

use App\Models\Shop\Product;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Actions\Products\Variations\ReindexVariationAction;

final class ReindexProductAction
{
    use AsAction;

    public function handle(Product $product)
    {
        $product->variations->sortByDesc('is_default')->sortByDesc('created_at')->each(fn($v) => ReindexVariationAction::run($v));
    }
}
