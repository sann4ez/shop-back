<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\MediaShowResource;
use App\Models\ProductVariation;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductVariationListResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = app()->getLocale();

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
            'switching' => $this->switching[$locale] ?? [],
            'states' => $this->getClientStates(),

//            'markers' => optional($this->product)->relationLoaded('markers')
//                ? ItemMarkerListResource::collection($this->getMarkers())
//                : [],
            'images' => $this->whenLoaded('media', fn () => MediaShowResource::collection($this->getImages('images'))),
            'product' => $this->whenLoaded('product', fn () => ProductListResource::make($this->product)),

//            'fields' => $this->when($val = \Domain::getOpt('variations.add_fields_show_in_list'), fn() => $this->getPrepareFields($val)),
        ];

//        $res['specification'] = match (\Domain::getOpt('variations.formats.specification')) {
//            'getAttributesPropertiesList2' => $this->whenLoaded('properties', fn()=> $this->getAttributesPropertiesList2()),
//            default => $this->whenLoaded('properties', fn()=> $this->getAttributesPropertiesListArray2('name', 'value')),
//        };

        return $res;
    }
}
