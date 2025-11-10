<?php

namespace App\Http\Client\Api\Resources;

use App\Http\Client\Api\Resources\MediaShowResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProfileEditResource extends JsonResource
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
            'lastname' => $this->lastname,
            'middlename' => $this->middlename,
            'birthday' => $this->birthday?->format('Y.m.d'),
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->roles->first()?->name, // TODO Vovna
            'status' => $this->status,
            'discount' => $this->discount,
            'locale_code' => $this->locale_code,
            'created_at' => $this->getDatetime('created_at'),
            'states' => [
                'is_online' => $this->isOnline(),
                'is_verified' => $this->hasVerifiedEmail(),
            ],
            'shipping' => $this->getAdded('shipping'),
            'payment' => $this->getAdded('payment'),
            'avatar' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('avatar'))),
        ];
    }
}
