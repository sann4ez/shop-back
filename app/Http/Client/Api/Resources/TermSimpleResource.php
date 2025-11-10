<?php

namespace App\Http\Client\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class TermSimpleResource  extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'variations_count' => $this->when($this->variations_count !== null, fn() => $this->variations_count),

            'aggregation' => $this->when($this->aggregation !== null, fn() => $this->aggregation), // доступній в фасетних фільтрах
            'is_allowed' => $this->when($this->is_allowed !== null, fn() => $this->is_allowed), // доступній в фасетних фільтрах
            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
            'logo' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('logo'))),
            'children' => $this->whenLoaded('children', fn() => TermResource::collection($this->children)),

        ];
    }
}
