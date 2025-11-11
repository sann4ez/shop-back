<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasSlugTrait;
//use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\HasDatetimeFormatterTz;
use Illuminate\Support\Arr;

class Block extends \Fomvasss\Blocks\Models\Block
{
    use HasFactory,
        HasDatetimeFormatterTz,
        HasStaticLists,
        HasSlugTrait;

    const STATUS_PUBLISHED = 'published';
    const STATUS_HIDDEN = 'hidden';

    protected $guarded = ['id'];

    protected $casts = [
        'ids' => 'array',
        'content' => 'array',
        'options' => 'array',
        'locales' => 'array',
    ];

    protected $attributes = [
        'status' => self::STATUS_PUBLISHED,
        'weight' => 1000,
    ];

    public $translatedAttributes = ['name', 'desc', 'content'];

    public $slugSeparator = '_';

    public static function getCacheName(string $slug): string
    {
        return md5(serialize("block-{$slug}-"));
    }

    /**
     * Доступні для виводу клієнту блоки.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeByAllowed(Builder $builder)
    {
        return $builder->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Список типів блоків.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function typesList(string $columnKey = null, string $indexKey = null): array
    {
        $status = [
            [
                'key' => 'slider',
                'name' => 'Верхній слайдер (головна)',
                'screen' => '',
            ],
            [
                'key' => 'variations',
                'name' => 'Товари',
                'screen' => '',
            ],
            [
                'key' => 'our_services',
                'name' => 'Наші послуги',
                'screen' => '',
            ],
            [
                'key' => 'implemented_projects',
                'name' => 'Приклади реалізованих проєктів',
                'screen' => '',
            ],
            [
                'key' => 'why_me',
                'name' => 'Чому ми',
                'screen' => '',
            ],
        ];

        return self::staticListBuild($status, $columnKey, $indexKey);
    }

    /**
     * Тип поточного блоку.
     *
     * @param string $column
     * @return string|array|null
     */
    public function getType(string $column = 'name'): string|array|null
    {
        return self::typesList($column, 'key')[$this->type] ?? null;
    }

    /**
     * Список статусів блоків.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function statusesList(string $columnKey = null, string $indexKey = null): array
    {
        $status = [
            [
                'key' => self::STATUS_PUBLISHED,
                'name' => 'Опубліковано',
            ],
            [
                'key' => self::STATUS_HIDDEN,
                'name' => 'Приховано',
            ],
        ];

        return self::staticListBuild($status, $columnKey, $indexKey);
    }

    /**
     * Статус поточного блоку.
     *
     * @param string $column
     * @return string|array|null
     */
    public function getStatus(string $column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->status] ?? null;
    }

    /**
     * Скрін блоку з фронта.
     *
     * @param string $typeData
     * @return string
     */
    public static function getScreen(array $typeData): string
    {
        $img = '/files/blocks/'. $typeData['key'] .'.png';
        $img = file_exists(public_path($img)) ? asset($img) : $typeData['screen'] ?? '';

        return $img;
    }

    public function scopeFilterable(Builder $builder, array $attrs = [], array $default = [])
    {
        $attrs = ($attrs ?: request()->all()) + $default;

        $models = Arr::get($attrs, 'models');
        $builder->when($models, function ($q) use ($models) {

            $q->whereIn('id', function ($query) use ($models) {
                $query->select('block_id')->from('blockable')->whereIn('model_type', Arr::wrap($models));
            });

            if (in_array('not_related', is_string($models) ? [$models] : $models)) {
                $q->orWhereNotIn('id', function ($query) {
                    $query->select('block_id')->from('blockable')->whereRaw('1 = 1');
                });
            }
        });

        $builder->when($val = Arr::get($attrs, 'q'), fn($q) => $q->whereTranslationLike('name', "%{$val}%"));
        $builder->when($val = Arr::get($attrs, 'status'), fn($b) => $b->whereIn('status', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'type'), fn($b) => $b->whereIn('type', Arr::wrap($val)));

        // Sort
        if ($sort = Arr::get($attrs, 'sort')) {
            $order = Arr::get($attrs, 'order');
            $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
            if (in_array($sort, ['created_at', 'type', 'name'])) {
                $builder->orderBy($sort, $order);
            } else {
                $builder->latest();
            }
        } else {
            $builder->latest();
        }

        $builder->when($val = Arr::get($attrs, 'limit'), fn($q) => $q->limit($val));
    }
}
