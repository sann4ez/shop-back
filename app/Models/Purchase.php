<?php

namespace App\Models;

use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Purchase extends Model
{
    use HasFactory,
        HasDatetimeFormatterTz,
        HasUuidPrimaryKey;

    protected $guarded = ['id'];

    protected $casts = [
        'added' => 'array',
        'discount' => 'float',
        'price' => 'float',
        'price_cost' => 'float',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function variation()
    {
        return $this->morphTo('variation', 'model_type', 'model_id');
    }

    public function variationWithTrashed()
    {
        return $this->variation()->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function freshProductData($model = null): bool
    {
        if ($model) {
            $this->model()->associate($model);
        }

        if ($this->model instanceof ProductVariation) {
            $variation = $this->model;

            $variation->load([
                'product.category',
                'properties.attribute',
                'product.media'
            ]);

//            if (empty($this->name)) {
//                $this->setAttribute('name', $product->getName());
//            }

            if (empty($this->price)) {
                $this->setAttribute('price', $variation->getPrice());
            }

            $this->setAttribute('price_cost', $variation->price_cost);

//            if (empty($this->currency_code)) {
//                $this->setAttribute('currency_code', $variation->currency_code);
//            }

            $this->setAttribute('added->variation', [
                'id' => $variation->id,
                'slug' => $variation->slug,
                'sku' => $variation->sku,
                'barcode' => $variation->barcode,
                'name' => $variation->getName(),
                'price' => $variation->price,
                //'currency_code' => $variation->currency_code,
                'img' => parse_url($variation->getImageUrl('images', 'thumb', '', [], true), PHP_URL_PATH),
                'prices' => $variation->getPrices(),
                'properties' => $variation->getAttributesPropertiesListStr(),
            ]);

           // $this->setAttribute('img', $variation->getImageBase64());

            return $this->saveQuietly();
        }

        return false;
    }


    /**
     * TODO: Deprecated
     *
     * @return float
     */
    public function totalSum(): float
    {
        return $this->getTotalSum();
    }

    /**
     * Суму позиції з врахуванням знижки в корзині.
     *
     * @return float
     */
    public function getTotalSum(): float
    {
        return round($this->price * $this->quantity - $this->discount, 2);
    }

    /**
     * Суму позиції без врахуванням знижки в корзині.
     *
     * @return float
     */
    public function getSum(): float
    {
        return round($this->price * $this->quantity, 2);
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->order->currency_code;
    }

    /**
     * Назва позиції.
     *
     * @return string
     */
    public function getName(): string
    {
        if ($this->relationLoaded('variation') && $this->variation) {
            return $this->variation->getName();
        }

        if ($this->relationLoaded('variationWithTrashed') && $this->variationWithTrashed) {
            return $this->variationWithTrashed->getName();
        }

        return $this->added['variation']['name'] ?? '';
    }

    public function getSku(): string
    {
        if ($this->relationLoaded('variation') && $this->variation) {
            return $this->variation->getSku();
        }

        if ($this->relationLoaded('variationWithTrashed') && $this->variationWithTrashed) {
            return $this->variationWithTrashed->getSku() . ' - DELETED';
        }

        return $this->added['variation']['sku'] ?? '';
    }

    /**
     * Ціна позиції.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getPriceCost(): float
    {
        return $this->price_cost;
    }

    /**
     * Знижка.
     *
     * @return float
     */
    public function getDiscountSum(): float
    {
        return $this->discount;
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->quantity;
    }

    public function getMinQty(): int
    {
        return $this->model?->min_qty ?? 0;
    }

    /**
     * Multiplicity
     * @return int
     */
    public function getStep(): int
    {
        return $this->model?->multiplicity ?? 0;
    }

    /**
     * WEB URL позиції.
     *
     * @return string
     */
    public function getUrlClient(): string
    {
        return $this->model?->getUrlClient() ?: '#';
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        //return $this->model->getImageUrl('images', 'thumb');
        if ($path = Arr::get($this->added, 'variation.img')) {
            return url($path);
        }

        return '';
    }
}
