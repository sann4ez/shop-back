<?php

namespace App\Models;

use App\Actions\Products\ReindexProductAction;
use App\Models\Item;
use App\Models\Traits\HasVariations;
use App\Models\Term;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasAddFields;
use App\Models\Traits\HasNavigable;
use App\Models\Traits\HasProperties;
use App\Models\Traits\HasSlugTrait;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasTaxonomies;
use App\Models\Traits\HasUuidPrimaryKey;
use App\Models\Traits\InteractsWithMedia;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Product extends Model implements HasMedia
{
    use HasFactory,
        HasStaticLists,
        HasTaxonomies,
        HasProperties,
        HasSlugTrait,
        InteractsWithMedia,
        HasVariations,
        HasDatetimeFormatterTz,
        HasUuidPrimaryKey,
        HasNavigable,
        HasAddFields,
        SoftDeletes;

    const TYPE_PRODUCT = 'product';
    const TYPE_COLLECTION = 'collection';

    const STATUS_PUBLISHED = 'published';
    const STATUS_HIDDEN = 'hidden';

    protected $guarded = ['id'];

    protected $casts = [
        'added' => 'array',
        'fields' => 'array',
        'variant_is_default' => 'boolean',
        'income_at' => 'datetime',
    ];

    protected array $mediaSingleCollections = ['image', 'video_poster'];
    protected array $mediaMultipleCollections = ['images', 'images2', 'videos'];

    public $translatedAttributes = ['name', 'body', 'fields'];

    protected static function booted(): void
    {
        static::created(function (self $product) {
            if (!$product->feed_id) {
                do {
                    $code = \Str::random(8);
                } while (self::where(['domain_id' => $product->domain_id, 'feed_id' => $code])->exists());

                $product->setAttribute('feed_id', $code)->saveQuietly();
            }
        });

        static::deleting(function (self $product) {
            $product->productVariations->each->delete();
        });
    }
    /**
     * Основна категорія товара.
     *
     * @return mixed
     */
    public function category()
    {
        return $this->term('category_id', 'id')->where('vocabulary', Term::VOCABULARY_PRODUCT_CATEGORIES);
    }

    /**
     * Бренд товара.
     *
     * @return mixed
     */
    public function brand()
    {
        return $this->term('brand_id', 'id')->where('vocabulary', Term::VOCABULARY_BRANDS);
    }

    /**
     * Категорії, в яких виводити, шукати, ...
     *
     * @return mixed
     */
    public function categories()
    {
        return $this->termsByVocabulary(Term::VOCABULARY_PRODUCT_CATEGORIES);
    }

    /**
     * Теги товару.
     *
     * @return mixed
     */
    public function tags()
    {
        return $this->termsByVocabulary(Term::VOCABULARY_TAGS);
    }

    /**
     * Маркери (NEW,  TOP, HOT,...)
     *
     * @return mixed
     */
    public function markers()
    {
        return $this->morphToMany(Item::class, 'itemable')->where('type', Item::TYPE_PRODUCT_MARKER);
    }

    /**
     * Парність.
     *
     * @return mixed
     */
    public function productparity()
    {
        return $this->term('productparity_id', 'id')->where('vocabulary', Term::VOCABULARY_PRODUCTPARITIES);
    }


    public function productmodel()
    {
        return $this->term('productmodel_id', 'id')->where('vocabulary', Term::VOCABULARY_PRODUCTMODELS);
    }

    public function scopeByAllowed(Builder $builder)
    {
        return $builder->whereStatus(self::STATUS_PUBLISHED)//->where(fn($b) => $b->whereJsonContains('locales', app()->getLocale()))
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

        return $this;
    }

    /**
     * Зв'язані.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRelateds()
    {
        if ($ids = Arr::wrap($this->getAdded('relateds'))) {
            $variations = self::whereIn('id', $ids)->with('translations', 'media', 'variation.translations', 'variation.media')->get(); // TODO

            return $variations->sortBy(fn ($item) => array_search($item->id, $ids));
        }

        return \collect([]);
    }

    /**
     * Рекомендовані.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRecommends(bool $orDefault = false)
    {
        if ($ids = Arr::wrap($this->getAdded('recommends'))) {
            $variations = self::whereIn('id', $ids)->with('translations', 'media', 'variation.translations', 'variation.media')->get(); // todo

            return $variations->sortBy(fn ($item) => array_search($item->id, $ids));
        }

        if ($orDefault) {
            // todo
        }

        return \collect([]);
    }

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
    public function getStatus($column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->status] ?? null;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function typesList(string $columnKey = null, string $indexKey = null): array
    {
        // TODO
        $records = [
            [
                'key' => self::TYPE_COLLECTION,
                'name' => 'Collection',
            ],
            [
                'key' => self::TYPE_PRODUCT,
                'name' => 'Product',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        return $this->rating ?: 0;
    }

    /**
     * @param $key
     * @param $default
     * @return array|\ArrayAccess|mixed
     */
    public function getAdded($key, $default = null)
    {
        return Arr::get($this->added ?? [], $key, $default);
    }

    /**
     * @return int
     */
    public function getCommentsCount(): int
    {
        return $this->comments_count ?: 0;
    }

    public function reIndex()
    {
        ReindexProductAction::run($this);
    }

    /**
     * Default variation URL.
     *
     * @param array $params
     * @return string
     */
    public function getUrlClient($params = []): string
    {
        if (!$this->relationLoaded('category')) {
            return '#';
        }
        if (empty($this->category)) {
            return '#';
        }
        if (!$this->relationLoaded('variation') || !$this->variation) {
            return '#';
        }

        $params = array_merge([$this->category, $this->variation], Arr::wrap($params));

        return route('catalog.variation.show', $params);
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        if (!$this->relationLoaded('variation') || !$this->variation) {
            return $this->name ?: '';
        }

        return $this->variation->getName();
    }

    /**
     * @return string
     */
    public function getTeaser(): string
    {
        return strip_tags(Str::limit( $this->body ?: '', 150, ''));
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body ?: '';
    }

    public function scopeFilterable(Builder $builder, array $params = [], array $default = [])
    {
        $params = ($params ?: request()->all()) + $default;

//        $builder->when($val = Arr::get($params, 'category'), fn ($q) => $q->whereIn('category_id', Arr::wrap($val)));
//        $builder->when($val = Arr::get($params, 'brand'), fn ($q) => $q->whereIn('brand_id', Arr::wrap($val)));
    }

    /**
     * @return array
     */
//    public function registerSeoDefaultTags(): array
//    {
//        $nameToken = \Domain::getOpt('variations.name_format', '[variation:name]');
//
//        return [
//            'title' => $nameToken,
//            'description' => '[product:body]',
//        ];
//    }
}
