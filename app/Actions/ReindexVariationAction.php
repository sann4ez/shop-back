<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\ProductVariation;
use App\VariationBuilder;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

/**
 * Створення локального індексу варіацій.
 */
final class ReindexVariationAction
{
    use AsAction, AsCommand;

    public string $commandSignature = 'variation:reindex {--id=all} {--force=}';
    public string $commandName = 'variation:reindex';

    public function handle(ProductVariation $variation)
    {
        $variation->load([
            'product',
            'product.variations',
            'product.variations.properties',
            'product.variations.properties.attribute',
            'product.category.ancestors',
            'product.category',
            'product.categories',
            'properties',
            'properties.attribute',
        ]);

        /** @var Product $product */
        $product = $variation->product;

        $categoryAncestorsIds = $product->category?->ancestors->pluck('id')->toArray() ?: [];
        $categoriesIds = $product->categories->pluck('id')->toArray();
        $categoriesIds[] = $product->category_id;
        $categoriesIds = array_merge(array_unique($categoriesIds), $categoryAncestorsIds);

        $facet = array_merge([
            'categories' => $categoriesIds,
            'tags' => $product->tags->pluck('id')->toArray(),
            'brands' => $product->brand ? [$product->brand->id] : [],
            'markers' => $variation->getMarkers()->pluck('id')->toArray(),
        ],
            $variation->getAttributesPropertiesListArray('id', 'id'),
        );

        $builder = new VariationBuilder($product->variations->all());
        $switching = $builder->buildSwitchingArray($variation);

        $variation->setAttribute('switching', $switching);
        $variation->setAttribute('income_at', $product->income_at);

        $variation->setAttribute('index', [
            'id' => $variation->id,
            'status' => $variation->status === ProductVariation::STATUS_PUBLISHED && $product->status === Product::STATUS_PUBLISHED
                ? ProductVariation::STATUS_PUBLISHED
                : ProductVariation::STATUS_HIDDEN,
            'sku' => mb_strtolower($variation->getSku()),
            'barcode' => mb_strtolower($variation->getBarcode()),
            'rating' => $product->getRating(),
            'created_at' => $variation->created_at?->timestamp,
            'income_at' => $variation->income_at?->timestamp ?: $product->income_at?->timestamp,
            'price' => $variation->price,
            'has_discount' => 0,
            'has_promotion' => 0,
            'has_parities' => 0,
            'facet' => $facet,
            'categories' => $categoriesIds,
            'category_id' => $product->category_id,
            'productparity_id' => $product->productparity_id,
            'productmodel_id' => $product->productmodel_id,
        ]);

        $variation->saveQuietly();
    }

    public function asCommand(Command $command)
    {
        $startTime = microtime(true);
        $n = 0;

        if ($command->option('id') === 'all') {
            if ($command->hasOption('force') || $command->confirm('Запустити для всіх варіацій?')) {
                ProductVariation::query()->chunk(100, function ($variations) use (&$n) {
                    foreach ($variations as $variation) {
                        $this->handle($variation);
                        GroupedAttributeVariationAction::run($variation);
                        ReindexAfterGroupedMainVariationAction::run($variation);
                        $n++;
                    }
                });

                $executionTime = microtime(true) - $startTime;
                $command->info("Завершення оновлення індексу {$n} варіацій, {$executionTime} сек. ✅");
            }
        } elseif ($command->option('id')) {
            $ids = explode(',', $command->option('id'));
            $variations = ProductVariation::whereIn('id', $ids)->get();

            if (!$variations->count()) {
                $command->warn('Варіацій не знайдено!');
                return false;
            }

            foreach ($variations as $variation) {
                $this->handle($variation);
                GroupedAttributeVariationAction::run($variation);
                ReindexAfterGroupedMainVariationAction::run($variation);
                $n++;
            }

            $executionTime = microtime(true) - $startTime;
            $command->info("Завершення оновлення індексу {$n} варіацій, {$executionTime} сек. ✅");
        } else {
            $command->warn('Некоректний параметр --id.');
            return false;
        }
    }
}
