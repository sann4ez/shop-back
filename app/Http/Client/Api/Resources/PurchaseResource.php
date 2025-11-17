<?php

namespace App\Http\Client\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'total' => $this->price * $this->quantity - $this->discount,
            'variation' => $this->whenLoaded('variation', fn() => ProductVariationListResource::make($this->variation)),
            //'currency_code' => $this->currency_code,
//            'created_at' => $this->getDatetime('created_at'),
//            'updated_at' => $this->getDatetime('updated_at'),
        ];
    }
}
