<?php

namespace App\Models\Traits;

use App\Models\ProductVariation;

trait HasVariations
{
    /**
     * For route bindings!
     *
     * @return mixed
     */
    public function productVariations()
    {
        return $this->hasMany(ProductVariation::class);
    }


    public function variations()
    {
        return $this->productVariations();
    }

    /**
     * Get default variation.
     *
     * @return mixed
     */
    public function variation()
    {
        return $this->hasOne(ProductVariation::class)
            ->where('is_default', true);
    }
}
