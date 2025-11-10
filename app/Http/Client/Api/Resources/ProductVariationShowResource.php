<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\MediaShowResource;
use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

final class ProductVariationShowResource extends JsonResource
{
    public function toArray($request)
    {
        $res = [
            'id' => $this->id,
            'entity' => 'variation',
            'name' => $this->getName(),
            'slug' => $this->slug,
            'sku' => $this->sku,
            'status' => $this->status,
            'multiplicity' => $this->multiplicity,
            'stock_qty' => $this->stock_qty,
            'min_qty' => $this->min_qty,

            'prices' => $this->getPrices(),
            'states' => $this->getClientStates(),

            'fields' => $this->getPrepareFields(),

//            'markers' => optional($this->product)->relationLoaded('markers')
//                ? ItemMarkerListResource::collection($this->getMarkers())
//                : [],

            'images' => $this->getImagesResource($this->getImages()),
            'product' => $this->whenLoaded('product', fn () => ProductShowResource::make($this->product)),
        ];

//        $res['specification'] = match (\Domain::getOpt('variations.formats.specification')) {
//            'getAttributesPropertiesList2' => $this->whenLoaded('properties', fn()=> $this->getAttributesPropertiesList2()),
//            default => $this->whenLoaded('properties', fn()=> $this->getAttributesPropertiesListArray2('name', 'value')),
//        };

        return $res;
    }

    protected function getImagesResource(Collection $medias)
    {
        $altTemplates = \App\Models\ProductVariation::getImagesSeoSettings();

        if (count($altTemplates)) {
            return $medias->map(function (Media $media, $index) use ($altTemplates) {
                $template = $altTemplates[$index]['alt'] ?? $index;
                $alt = \StrToken::setEntities(['product' => $this->product, 'variation' => $this->resource])->setText($template)->replace();
                return MediaShowResource::make($media->setAttribute('alt', $alt));
            });
        }

        return MediaShowResource::collection($medias);
    }
}
