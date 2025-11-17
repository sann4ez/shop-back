<?php

namespace App\Http\Client\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            //'status_obj' => $this->getStatus('*'),
            //'payment_status' => $this->payment_status,
            //'ttn' => $this->ttn,

            'created_at' => $this->getDatetime('created_at'),
            'ordered_at' => $this->getDatetime('ordered_at'),
            'status_data' => $this->getStatus('*'),
            'purchases' => $this->whenLoaded('purchases', fn () => PurchaseResource::collection($this->purchases)),
            //'discounts' => $this->whenLoaded('discounts', fn () => DiscountResource::collection($this->discounts)),
            'total' => $this->getTotalInfo(),
        ];
    }
}
