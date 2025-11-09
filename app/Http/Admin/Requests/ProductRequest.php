<?php

namespace App\Http\Admin\Requests;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Rules\PriceOldValidation;
use App\Rules\UniqueModel;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $productId = $this->route('product')?->id;

        $res = [
            'name' => 'nullable|string|max:255',
            'status' => ['sometimes', 'required', Rule::in(Product::statusesList('key')), 'max:255'],
            'body' => ['nullable', 'string', 'max:1000000'],
            'added' => 'nullable|array',
            'fields' => 'nullable|array',
            'markers' => 'nullable|array',
            'variation' => 'sometimes|array',
            //'variation.sku' => 'unique',
        ];

        if ($this->has('variation')) {
            $variationId = $this->input('variation_id');
            $res = \array_merge($res, [
                'variation.sku' => array_merge(['sometimes', 'required', 'string'], [
                     new UniqueModel(ProductVariation::class, 'sku', $variationId),  // TODO Domain
                ]),
//                'variation.barcode' => array_merge(\Domain::getOpt('variations.barcode.validation', []), [
//                     new UniqueModel(ProductVariation::class, 'barcode', $variationId), // TODO Domain
//                ]),
                'variation.name' => ['nullable', 'string', 'max:255',],
                'variation.body' => ['nullable', 'string', 'max:1000000'],
                'variation.price' => ['numeric', 'between:0,99999999',],
                'variation.price_old' => ['numeric', 'between:0,99999999'],
                'variation.price_cost' => ['sometimes', 'numeric', 'between:0,99999999',],
                'variation.barcodes' => ['nullable', 'string', 'max:255',],
                'variation.stu_extern' => ['nullable', 'string', 'max:255',],
                'variation.stock_qty' => ['sometimes', 'integer', 'between:-999999999,999999999'],
                'variation.min_qty' => ['sometimes', 'integer', 'between:0,999999999'],
                'variation.limit_qty' => ['sometimes', 'integer', 'between:0,999999999'],
                'variation.multiplicity' => ['sometimes', 'integer', 'between:0,999999999'],
            ]);
        }

        return $res;
    }

    protected function prepareForValidation()
    {
        $this->prepareForValidationDatetimeValues('income_at');
    }
}
