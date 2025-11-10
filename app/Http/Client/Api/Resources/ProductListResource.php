<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\TermSimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            //'name' => $this->name,
            //'teaser' => $this->getTeaser(),
            'rating' => $this->getRating(),
            //'comments_count' => $this->when(!is_null($this->root_comments_count), fn () => $this->root_comments_count),
            'comments_count' => $this->comments_count ?: 0,
            'income_at' => $this->getDatetime('income_at'),
            //'created_at' => $this->getDatetime('created_at'),

            'productmodel' => $this->whenLoaded('productmodel', fn () => TermSimpleResource::make($this->productmodel)),
            'productparity' => $this->whenLoaded('productparity', fn () => TermSimpleResource::make($this->productparity)),
            'category' => $this->whenLoaded('category', fn () => TermSimpleResource::make($this->category)),
//            'brand' => $this->whenLoaded('brand', fn () => TermSimpleResource::make($this->brand)),
//            'tags' => $this->whenLoaded('tags', fn () => TermSimpleResource::collection($this->tags)),
//            'images' => $this->whenLoaded('media', fn () => MediaShowResource::collection($this->getMedia('images'))),
//            'variation' => $this->whenLoaded('variation', fn () => ProductVariationShowResource::make($this->variation)),
//            'variation' => $this->when($this->variation !== null, fn () => ProductVariationShowResource::make($this->variation)),
            //'variations' => $this->whenLoaded('variations', fn () => ProductVariationListResource::collection($this->variations)),
//            'fields' => $this->when($val = \Domain::getOpt('products.add_fields_show_in_list'), fn() => $this->getPrepareFields($val)),
        ];
    }
}
