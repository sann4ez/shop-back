<?php

namespace App\Models;

use App\Models\Traits\HasSlugTrait;
use App\Models\Traits\HasUuidPrimaryKey;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Fomvasss\MediaLibraryExtension\HasMedia\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Property extends Model implements HasMedia
{
    use HasFactory,
        HasUuidPrimaryKey,
        InteractsWithMedia,
        HasSlugTrait;

    protected $guarded = ['id'];

    public $slugSourceColumns = ['value'];

    protected array $mediaSingleCollections = ['image'];

    protected $attributes = [
        'weight' => 1000,
    ];

    protected $casts = [
        'value' => 'string',
    ];

    protected static function booted()
    {
        static::creating(function (self $model) {
            $maxWeight = self::where('attribute_id', $model->attribute_id)->max('weight') ?? -1;
            $model->setAttribute('weight', ++$maxWeight);
        });

//        static::created(function (self $model) {
//            if (!$model->feed_id) {
//                do {
//                    $code = \Str::random(8);
//                } while (self::whereHas('attribute', fn($q) => $q->where('domain_id', $model->attribute->domain_id))->where('feed_id', $code)->exists());
//
//                $model->setAttribute('feed_id', $code)->saveQuietly();
//            }
//        });
    }

    /**
     * @return BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return implode(' ', array_filter([
            $this->prefix, $this->value, $this->suffix,
        ]));
    }
}
