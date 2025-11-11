<?php

namespace App\Http\Client\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class AttributeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->getName(),
            'format' => $this->has_image ? 'image' : 'text',

            'is_allowed' => $this->when($this->is_allowed !== null, fn() => $this->is_allowed), // доступній в фасетних фільтрах
            'properties' => $this->whenLoaded('properties', fn() => PropertyResource::collection($this->properties->sortBy('weight'))),
        ];
    }
}
