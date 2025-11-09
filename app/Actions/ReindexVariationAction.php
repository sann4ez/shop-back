<?php

namespace App\Actions;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductVariation;
use App\VariationBuilder;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Створення локального індексу варіацій.
 */
final class ReindexVariationAction
{
    use AsAction;

    public string $commandSignature = 'variation:reindex {--id=all} {--force=}';

    public function handle(ProductVariation $variation)
    {
        $variation->load([
            'translations',
            'product',
            'promotions',

            // для побудови переключалки (buildSwitchingArray), атрибути з картинками
            'product.variations.product.translations',
            'product.variations.properties.media',
            'product.variations.translations',
            'product.variations.properties.translations',
            'product.variations.properties.attribute.translations',

            'product.category.ancestors',
            'product.category.promotions',
            'product.brand.promotions',
            'product.categories',
            'product.markers',
            'product.tags',
            'properties.translations',
            'properties.attribute.translations',
        ]);

        /** @var Product $product */
        $product = $variation->product;

        $categoryAncestorsIds = $product->category?->ancestors->pluck('id')->toArray() ?: [];
        $categoriesIds = $product->categories->pluck('id')->toArray();
        $categoriesIds[] = $product->category_id;
        $categoriesIds = array_merge(\array_unique($categoriesIds), $categoryAncestorsIds);

        // https://i.ibb.co/GXLvbWG/2025-09-26-14-04.jpg
        $facet = array_merge([
            'categories' => $categoriesIds,
            'tags' => $product->tags->pluck('id')->toArray(),
            'brands' => $product->brand ? [$product->brand->id] : [],
            'markers' => $variation->getMarkers()->pluck('id')->toArray(),
        ],
            //$product->getAttributesPropertiesListArray('id', 'id'),
            $variation->getAttributesPropertiesListArray('id', 'id'), // attributes & properties
        );

        $translateIndexes = [];
        $switching = [];
        $locales = \Domain::getSupportedLocalesCodes();
        foreach ($locales as $key) {
            // назва варіації
            $vname = ($variation->translateOrDefault($key)?->name) ?? '';
            $translateIndexes['vname_'.$key] = mb_strtolower($vname);   // для сортування по назві варіації

            // назва товару (групи)
            $name = trim(($product->getTranslationsArray()[$key]['name'] ?? '') . ' ' . ($variation->getTranslationsArray()[$key]['name'] ?? ''), ' ');
            $translateIndexes['name_'.$key] = mb_strtolower($name);     // для сортування по назві товара

            $body = strip_tags(($product->getTranslationsArray()[$key]['body'] ?? '') . ' ' . ($variation->getTranslationsArray()[$key]['body'] ?? ''));
            $translateIndexes['q_'.$key] = mb_strtolower($name . ' ' . $body . ' ' .  $variation->getSku() . ' ' . $variation->getSkuExtern() . ' ' . $variation->getBarcode()  . ' ' . $variation->getBarcodes());

            config()->set('translatable.locale', $key);
            $builder = new VariationBuilder($product->variations->all());
            $switching[$key] = $builder->buildSwitchingArray($variation);
        }

        $variation->setAttribute('switching', $switching);
        $variation->setAttribute('income_at', $product->income_at); // при оновлення в товарі - оновляти в усіх варіаціях


        // визначаємо варіацію (яка є основною в підгрупі чи головну в групі), яка має пари, яка буде виведедена в категорії парності
        // якщо є - позначаємо її як парний, ін. варіації групи позначаємо як не парні (щоб в категорії парності не виводилися)
        if (\Domain::getOptIs('products.fields.productparity') && ($variation->is_attribute_groped)) {
            $hasParities = $variation->getParities()->count();

            // для сортування при виведенні пар https://i.imgur.com/5CmWE5O.png
            $parityPropertyWeight = 0;
            $attributeGropedId = $variation->getAttributeGropedId();
            if ($attributeGropedId && Attribute::find($attributeGropedId)) {
                $parityPropertyWeight = $variation->properties->where('attribute_id', $attributeGropedId)->first()?->weight ?: 0;
            }
            $paritysort = (($product->productmodel?->weight ?? 0) * 10000  + $parityPropertyWeight * 100 + $product->productparity?->weight) ?? 0;

//            $variation->product->variations->where('id', '<>', $variation->id)->where('is_attribute_groped', false)
//                ->each(fn($v) => $v->setAttribute('index->has_parities', 0)->saveQuietly());
        } else {
            $hasParities = 0;
            $paritysort = 0;
        }

        if (config('services.elasticsearch.active')) {
            $variation->updateDocument([
                'has_parities' => (bool) $hasParities,
                'paritysort' => $paritysort,
                'productparity_id' => $product->productparity_id,
                'productmodel_id' => $product->productmodel_id,
            ]);
        }

        $variation->setAttribute('index', [
                'id' => $variation->id,
                'status' => $variation->status === ProductVariation::STATUS_PUBLISHED && $variation->product->status === Product::STATUS_PUBLISHED
                    ? ProductVariation::STATUS_PUBLISHED
                    : ProductVariation::STATUS_HIDDEN,
                'sku' => mb_strtolower($variation->getSku()),
                'barcode' => mb_strtolower($variation->getBarcode()),
                'rating' => $product->getRating(),
                'created_at' => $variation->created_at?->timestamp,
                'income_at' => $variation->income_at?->timestamp ?: $variation->product->income_at?->timestamp,
                'price' => $variation->price,
                'has_discount' => $variation->getPriceOld() > 0.0 ? 1 : 0,            // має знижку
                'has_promotion' => $variation->getPrices('promotion') ? 1 : 0, // бере участь в акції
                'has_parities' => $hasParities ? 1 : 0,                               // має парні варіації (виводити в категорії парності)

                'facet' => $facet, // TODO: Deprecated, use next in filters
                'categories' => $categoriesIds,
                'category_id' => $product->category_id,
                'tags' => $product->tags->pluck('id')->toArray(),
                'markers' => $variation->getMarkers()->pluck('id')->toArray(),
                'brands' => $product->brand_id,
                'brand_id' => $product->brand ? $product->brand->id : '',

                'productparity_id' => $product->productparity_id,   // для формування/пошуку парних
                'productmodel_id' => $product->productmodel_id,     // для формування/пошуку парних
                'paritysort' => $paritysort,                        // для впорядкування пар при виведенні
            ] + $translateIndexes);

        $variation->saveQuietly();
    }

    public function asCommand(Command $command)
    {
        $startTime = microtime(true);
        $n = 0;

        if ($command->option('id') === 'all') {
            if ($command->hasOption('force') || $command->confirm('Запустити для всіх варіацій?')) {
//                OperationResult::info('Старт оновлення індексу варіацій...⏳');

                ProductVariation::query()->chunk(100, function ($variations) use (&$n) {
                    foreach ($variations as $variation) {
                        $this->handle($variation);
                        GroupedAttributeVariationAction::run($variation);
                        ReindexAfterGroupedMainVariationAction::run($variation);
                        $n++;
                    }
                });

                $executionTime = microtime(true) - $startTime;
                OperationResult::info("Завершення оновлення індексу {$n} варіацій, {$executionTime} сек. ✅");
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
