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
            'created_at' => $this->getDatetime('created_at'),
            'income_at' => $this->getDatetime('income_at'),
            'category' => $this->whenLoaded('category', fn () => TermSimpleResource::make($this->category)),

            'images' => $this->whenLoaded('media', fn () => MediaShowResource::collection($this->getMedia('images'))),

            'fields' => $this->getPrepareFields(),
        ];
    }
}
