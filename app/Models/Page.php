<?php

namespace App\Models;

use Fomvasss\Blocks\Models\HasBlocks;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasSlugTrait;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Page extends Model
{
    use HasFactory,
        HasStaticLists,
        HasSlugTrait,
        HasUuidPrimaryKey,
        HasDatetimeFormatterTz,
        HasBlocks;

    const STATUS_PUBLISHED = 'published';
    const STATUS_HIDDEN = 'hidden';

    const TEMPLATE_SYSTEM = 'system';

    const SLUG_HOME_PAGE = 'home';

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => self::STATUS_PUBLISHED,
    ];

    protected $casts = [
        'added' => 'array',
    ];

    public $translatedAttributes = ['name', 'body'];

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function statusesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => self::STATUS_PUBLISHED,
                'name' => 'Опубліковано',
            ],
            [
                'key' => self::STATUS_HIDDEN,
                'name' => 'Приховано',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * @param string $column
     * @return string|array|null
     */
    public function getStatus(string $column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->status] ?? null;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeByAllowed(Builder $builder)
    {
        return $builder->where('status', self::STATUS_PUBLISHED)
                ->where('template', '<>', self::TEMPLATE_SYSTEM)
            //->where(fn($b) => $b->whereJsonContains('locales', app()->getLocale()))
            ;
    }

    /**
     * @return $this
     */
    public function checkAllowed()
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            abort(404);
        }

        if ($this->template === self::TEMPLATE_SYSTEM) {
            abort(404);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }

        return true;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function templatesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => 'default',
                'name' => 'По замовчуванню',
                'blocks' => [],
            ],
            [
                'key' => 'home',
                'name' => 'Головна',
                'blocks' => [],
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }
}
