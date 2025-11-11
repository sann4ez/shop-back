<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\MediaShowResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class PropertyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            //'weight' => $this->weight, // TODO: Deprecated?
            'value' => $this->getValue(),
            'color' => $this->color,
            //'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getFirstMedia('image'))),
            'image' => $this->whenLoaded('media', fn () => $this->getFirstMediaUrl('image')),
            'aggregation' => $this->when($this->aggregation !== null, fn() => $this->aggregation), // доступній в фасетних фільтрах
            'is_allowed' => $this->when($this->is_allowed !== null, fn() => $this->is_allowed), // доступній в фасетних фільтрах
        ];
    }
}
