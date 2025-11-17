<?php

namespace App\Http\Admin\Requests;

use App\Models\Payment;
use Illuminate\Validation\Rule;

final class PaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'operation' => ['required', Rule::in(Payment::operationsList('key'))],
            'gateway' => ['required', Rule::in(Payment::gatewaysList('key'))],
            'status' => ['required', Rule::in(Payment::statusesList('key'))],
            'source' => ['nullable', Rule::in(Payment::sourcesList('key'))],
            'category' => ['nullable', Rule::in(Payment::categoriesList('key'))],
            'model_id' => ['nullable'],
            'user_id' => ['nullable'],
            'paid_at' => ['nullable', 'date'],
            'comment' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:1', 'max:10000000000'],
        ];

        return $rules;
    }

    public function getData(): array
    {
        return $this->only('operation', 'gateway', 'source', 'category', 'model_id', 'user_id', 'paid_at', 'comment', 'status');
    }
}
