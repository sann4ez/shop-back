<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\MediaShowResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductCategoryShowResource  extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'entity' => $this->vocabulary,
            'slug' => $this->slug,
            'name' => $this->name,
            'body' => $this->body,
            'icon' => $this->getAdded('icon'),
            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
            'logo' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('logo'))),
            'children' => $this->whenLoaded('children', fn() => ProductCategoryListResource::collection($this->children)),
            'filter' => ['groped_type' => $this->getAdded('only_parities') ? 'parities' : null],
        ];
    }
}
