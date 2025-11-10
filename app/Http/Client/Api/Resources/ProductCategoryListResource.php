<?php

namespace App\Http\Client\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ProductCategoryListResource  extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'variations_count' => $this->variations_count,
            'icon' => $this->getAdded('icon'),
            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
            'logo' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('logo'))),
            'children' => $this->whenLoaded('children', fn() => ProductCategoryListResource::collection($this->children)),
        ];
    }
}
