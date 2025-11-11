<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\MediaShowResource;
use App\Models\ProductVariation;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductVariationListResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var ProductVariation $this */
        $res = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->getNameList(),
            'sku' => $this->sku,
            'status' => $this->status,
            'multiplicity' => $this->multiplicity,
            'stock_qty' => $this->stock_qty,
            'min_qty' => $this->min_qty,

            'prices' => $this->getPrices(),
            'states' => $this->getClientStates(),

            'images' => $this->whenLoaded('media', fn () => MediaShowResource::collection($this->getImages('images'))),
            'product' => $this->whenLoaded('product', fn () => ProductListResource::make($this->product)),
        ];

        return $res;
    }
}
