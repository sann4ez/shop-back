<?php

namespace App\Http\Client\Api\Resources\Profile;

use App\Http\Client\Api\Resources\MediaShowResource;
use App\Http\Client\Api\Resources\Terms\TermSimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProfileShowResource extends JsonResource
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
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'created_at' => $this->getDatetime('created_at'),
            'is_online' => $this->isOnline(),
            'is_verified' => $this->hasVerifiedEmail(),
            'activity_at' => $this->activity_at,

            //'avatar' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('photos'))),
        ];
    }
}
