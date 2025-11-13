<?php

namespace App\Blocks;

use App\Http\Client\Api\Resources\ProductVariationListResource;
use App\Models\ProductVariation;
use Fomvasss\Blocks\Contracts\BlockHandlerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class VariationsBlockHandler implements BlockHandlerInterface
{
    public static function getType(): string
    {
        return 'variations';
    }

    public function handle(Model $block, array $attrs = []): array
    {
        $productVariationWith = [
            'media',
            'product.media',
            'product.category',
            'properties.attribute',
        ];

        $filter = [
            'sort' => $block->getOptions('sort'),
            'order' => $block->getOptions('order'),
            'limit' => $block->getOptions('limit') ?: 10,
            'has_discount' => $block->getOptions('has_discount'),
            'has_promotion' => $block->getOptions('has_promotion'),
            'groped_type' => $block->getOptions('groped_type'),
            'status' => ProductVariation::STATUS_PUBLISHED,
            'in_stock' => match ($block->getOptions('in_stock')) {
                '3' => false,
                '2' => true,
                default => null, //1
            },
            'ids' =>  $block->getIds('variations'),
            '_by' => 'id',
            'sort_reality' => 1,
        ];

        $variations = ProductVariation::with($productVariationWith)
            ->filterable($filter)->get();

        $data['variations'] = ProductVariationListResource::collection($variations);

        return $data;
    }
}
