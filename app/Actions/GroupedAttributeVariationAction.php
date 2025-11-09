<?php

namespace App\Actions;

use App\Models\Attribute;
use App\Models\ProductVariation;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Групування варіацій товару (групи) по вказаному атрибуту чи ін.
 */
final class GroupedAttributeVariationAction
{
    use AsAction;

    public string $commandSignature = 'variation:grouped-attribute {--id=all}';

    public function handle(ProductVariation $productVariation): bool
    {
        // Тип групування для каталого/списків
        $gropedType = ProductVariation::GROUPING_TYPE_SINGLE;
        if (!in_array($gropedType, [ProductVariation::GROUPING_TYPE_ATTRIBUTE, ProductVariation::GROUPING_TYPE_SINGLE, ProductVariation::GROUPING_TYPE_MAIN])) {
            return false;
        }

        // Не опубліковано варіація не групується
        if ($productVariation->status !== ProductVariation::STATUS_PUBLISHED) {
            return false;
        }

        // Беремо одну варіацію з групи - найбільш наявну або основну
        if ($gropedType === ProductVariation::GROUPING_TYPE_MAIN) {
            $variations = $this->sortVariationsGroup($productVariation);
            if ($variations->count() > 1) {
                $firtsVariation = $variations->first();
                foreach ($variations as $i => $variation) {
                    $variation->setAttribute('is_attribute_groped', $i === 0);
                    $variation->setAttribute('grouped_id', $firtsVariation->id);
                    $variation->saveQuietly();
                }
            } else {
                $productVariation->setAttribute('is_attribute_groped', true);
                $productVariation->setAttribute('grouped_id', $productVariation->id);
                $productVariation->saveQuietly();
            }

            return true;
        }

        // Вказаний ID (в категорії чи в товарі) атрибуту для групування в каталозі, н-д: Колір
        $attributeId = $productVariation->getAttributeGropedId();
        if ($attributeId && Attribute::find($attributeId)) {

            /** @var Collection $variations */
            $variations = $this->sortVariationsGroup($productVariation);

            $gropeds = [];

            /** @var ProductVariation $variation */
            foreach ($variations as $variation) {

                $groupedId = null; // ідентифікатор підгрупи варіацій (щось спільне для цих варіацій)

                // значення атрибуту групування, н-д: Зелений
                if ($property = $variation->properties->where('attribute_id', $attributeId)->first()) {
                    $groupedId = $property->id;

                    if (!in_array($property->id, $gropeds) && $variation->properties->contains('id', $property->id)) {
                        $variation->setAttribute('is_attribute_groped', true);
                    } else {
                        $variation->setAttribute('is_attribute_groped', false);
                    }

                    $gropeds[] = $property->id;

                // не має значення атрибуту, по якому задано групування, тому групованано одна сама ж варіація
                } else {
                    $groupedId = $variation->id;
                    $variation->setAttribute('is_attribute_groped', true);
                }

                $variation->setAttribute('grouped_id', $groupedId);
                $variation->saveQuietly();
            }

            return true;
        }

        // якщо варіації групи (категорія товара!) мають тільки один атрибут варіативності (Колір), то беремо одну з варіацій цієї групи (дефолтну або по більшій наявності)
        if ($gropedType === ProductVariation::GROUPING_TYPE_SINGLE) {
            $groupedAttrs = $productVariation->product->category?->attrs->where('in_variant', true);
            if ($groupedAttrs && $groupedAttrs->count() === 1) {
                foreach ($this->sortVariationsGroup($productVariation) as $i => $variation) {
                    $variation->setAttribute('is_attribute_groped', $i === 0);
                    $variation->setAttribute('grouped_id', $groupedAttrs->first()->id);
                    $variation->saveQuietly();
                }
                return true;
            }
        }

        // ID атрибуту для групування не вказаний, тому кожна варіація це окрема група (виводяться всі)
        $productVariation->product->variations->where('status', ProductVariation::STATUS_PUBLISHED)->each(function ($variation) {
            $variation->setAttribute('grouped_id', $variation->id);
            $variation->setAttribute('is_attribute_groped', true);
            $variation->saveQuietly();
        });

        return true;
    }

    public function asCommand(Command $command)
    {
        $variations = collect();

        if ($command->option('id') === 'all') {
            if ($command->confirm('Запустити для всіх варіацій?')) {
                $variations = ProductVariation::where('is_default', true)->with('product.variations.properties', 'product.category')->get();
            }
        } elseif ($command->option('id')) {
            $ids = explode(',', $command->option('id'));
            $variations = ProductVariation::whereIn('id', $ids)->with('product.variations.properties', 'product.category')->get();
        }

        if (!$variations->count()) {
            $command->warn('Варіацій не знайдено!');
            return false;
        }

        $n = 0;
        /** @var ProductVariation $variation */
        foreach ($variations as $variation) {
            GroupedAttributeVariationAction::run($variation);
            $n++;
        }

        $command->info("Опрацьовано {$n} варіацій!");
    }

    protected function sortVariationsGroup($productVariation): Collection
    {
        /** @var Collection $variations */
        $variations = $productVariation->product->variations->where('status', ProductVariation::STATUS_PUBLISHED)->sortBy([
            ['stock_qty', 'desc'],
            ['is_default', 'desc'],
            ['created_at', 'asc'],
        ])->values();

        return $variations;
    }
}
