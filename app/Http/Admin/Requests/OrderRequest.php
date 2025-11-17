<?php

namespace App\Http\Admin\Requests;

use App\Models\Order;
use App\Rules\Phone;
use Illuminate\Validation\Rule;

final class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['sometimes', Rule::in(Order::statusesList('key'))],
            'perform' => ['sometimes', Rule::in(Order::performsList('key'))],
            'client_comment' => 'nullable|string',
            'manager_comment' => 'nullable|string',
            'delivery_sum'  => 'nullable|numeric',
            'delivery_discount_sum', 'nullable|numeric',
            'discount_sum' => 'nullable|numeric',
            'locale_code' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'source' => 'nullable|string',
            'recipient.phone' => ['nullable', new Phone()],
        ];
    }

    /**
     * @return array|null[]|string[]
     */
    public function getData()
    {
        return array_merge([
            'manager_id' => $this->user()?->id,
            'locale_code' => app()->getLocale(),
        ], $this->validated(), [
            'delivery_sum' => $this->delivery_sum ?: 0,
            'delivery_discount_sum' => $this->delivery_discount_sum ?: 0,
            'discount_sum' => $this->discount_sum ?: 0,
            'type' => $this->type ?? Order::TYPE_CART,
        ]);
    }
}
