<?php

namespace App\Http\Admin\Requests;

use App\Rules\PriceOldValidation;
use App\Rules\ProductVariantProperties;
use Illuminate\Validation\Rule;
use App\Rules\UniqueModel;
use App\Models\Shop\ProductVariation;

class ProductVariationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $variationId = $this->product_variation?->id; //optional($this->route('product_variation'))->id;
        $productId = $this->product_id;

        $res = [
            'product_id' => ['sometimes', 'exists:products,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:1000000'],
            'status' => ['sometimes', 'required', 'max:255'],
            'sku' => array_merge(\Domain::getOpt('variations.sku.validation', []), [
                new UniqueModel(ProductVariation::class, 'sku', $variationId),  // TODO Domain
            ]),
            'barcode' => array_merge(\Domain::getOpt('variations.barcode.validation', []), [
                new UniqueModel(ProductVariation::class, 'barcode', $variationId), // TODO Domain
            ]),
            'sku_extern' => ['nullable', 'string', 'max:255'],
            'price' => ['numeric', 'between:0,99999999'],
            'price_old' => ['sometimes', 'numeric', 'between:0,99999999', new PriceOldValidation($this->price)],
            'price_cost' => ['sometimes', 'numeric', 'between:0,99999999'],
            'barcodes' => ['nullable', 'string', 'max:255'],
            'variation.stock_qty' => ['sometimes', 'integer', 'between:-999999999,999999999'],
            'min_qty' => ['sometimes', 'integer', 'between:0,999999999'],
            'limit_qty' => ['sometimes', 'integer', 'between:0,999999999'],
            'multiplicity' => ['sometimes', 'integer', 'between:0,999999999'],
            'slug' => [
                'nullable',
                'string',
                'alpha_dash',
                Rule::unique('product_variations')->ignore($variationId),
            ],
            'properties' => [
                'nullable',
                'array',
                new ProductVariantProperties($productId, $variationId)
            ],
            'added' => ['nullable', 'array'],
            'fields' => ['nullable', 'array'],
        ];

        return $res;
    }

    protected function prepareForValidation()
    {
        // if ($this->isEmptyString('sku') && $this->get('barcode')) {
        //     $this->merge([
        //         'sku' => $this->get('barcode'),
        //     ]);
        // }
    }
}
