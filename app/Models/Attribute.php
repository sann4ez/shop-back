<?php

namespace App\Models;

use App\Models\Traits\HasSlugTrait;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory,
        HasUuidPrimaryKey,
        HasStaticLists,
        HasSlugTrait;

    const USES_IN_FILTER = 'in_filter';
    const USES_IN_VARIANT = 'in_variant';
    const USES_IN_SPECIFICATION = 'in_specification';

    protected $guarded = ['id'];

    protected $casts = [
        'has_image' => 'boolean',
        'in_filter' => 'boolean',
        'in_variant' => 'boolean',
        'in_specification' => 'boolean',
    ];

    protected $attributes = [
        'weight' => 1000,
    ];

    protected static function booted()
    {
        static::creating(function (self $model) {
            $maxWeight = $model->max('weight') ?? -1;
            $model->setAttribute('weight', ++$maxWeight);
        });

//        static::created(function (self $model) {
//            if (!$model->feed_id) {
//                do {
//                    $code = \Str::random(8);
//                } while (self::where(['domain_id' => $model->domain_id, 'feed_id' => $code])->exists());
//
//                $model->setAttribute('feed_id', $code)->saveQuietly();
//            }
//        });
    }

    /**
     * @return HasMany
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function usesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'column' => 'in_filter',
                'key' => self::USES_IN_FILTER,
                'label' => 'Фільтр',
                'max' => '100',
                'default' => true,
                'fa_icon' => 'fa fa-filter',
            ],
            [
                'column' => 'in_variant',
                'key' => self::USES_IN_VARIANT,
                'label' => 'Варіації',
                'max' => '1',
                'fa_icon' => 'fa fa-futbol'
            ],
//            [
//                'column' => 'in_specification',
//                'key' => self::USES_IN_SPECIFICATION,
//                'label' => 'В описі',
//                'max' => '100',
//                'fa_icon' => 'fa fa-columns',
//            ],
            [
                'column' => 'has_image',
                'key' => 'has_image',
                'label' => 'З фото',
                'max' => '100',
                'fa_icon' => 'fa fa-palette',
            ],
        ];

        $options = ['except' => []];
//        if (\Domain::getOpt('products.has_variations') === false) {
            $options['except'][] = self::USES_IN_VARIANT;
//        }

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    public function inUses(string $val): bool
    {
        return in_array($val, $this->uses ?? []);
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?: '';
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->has_image ? 'image' : 'text';
    }

    /**
     * @param string $format
     * @return bool
     */
    public function isFormat(string $format): bool
    {
        return $this->getFormat() === $format;
    }
}
