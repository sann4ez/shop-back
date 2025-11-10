<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\MediaShowResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class TermResource  extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'body' => $this->body,

            'created_at' => $this->getDatetime('created_at'),
            //'addition' => $this->when($this->pivot, fn() => intval($this->pivot->addition)),
            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
            'logo' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('logo'))),
        ];
    }
}
