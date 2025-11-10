<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\MediaShowResource;
use App\Http\Client\Api\Resources\TermSimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductShowResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            //'name' => $this->name,
            'body' => $this->body,
            'rating' => $this->getRating(),
            'comments_count' => $this->comments_count ?: 0,
            //'created_at' => $this->getDatetime('created_at'),
            'income_at' => $this->getDatetime('income_at'),
            'brand' => $this->whenLoaded('brand', fn () => TermSimpleResource::make($this->brand)),
            'productmodel' => $this->whenLoaded('productmodel', fn () => TermSimpleResource::make($this->productmodel)),
            'productparity' => $this->whenLoaded('productparity', fn () => TermSimpleResource::make($this->productparity)),
            'category' => $this->whenLoaded('category', fn () => TermSimpleResource::make($this->category)),
            'tags' => $this->whenLoaded('tags', fn () => TermSimpleResource::collection($this->tags)),

            //'images' => $this->whenLoaded('media', fn () => MediaShowResource::collection($this->getMedia('images'))),
//            'variation' => $this->whenLoaded('variation', fn () => ProductVariationShowResource::make($this->variation)),
//            'variation' => $this->when($this->variation !== null, fn () => ProductVariationShowResource::make($this->variation)),
            //'variations' => $this->whenLoaded('variations', fn () => ProductVariationShowResource::collection($this->variations)),

            'fields' => $this->getPrepareFields(),
        ];
    }
}
