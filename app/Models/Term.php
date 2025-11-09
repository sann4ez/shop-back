<?php

namespace App\Models;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasNavigable;
use App\Models\Traits\HasSlugTrait;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasUuidPrimaryKey;
use App\Models\Traits\InteractsWithMedia;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class Term extends \Fomvasss\SimpleTaxonomy\Models\Term implements HasMedia
{
    use HasSlugTrait,
        InteractsWithMedia,
        HasDatetimeFormatterTz,
        HasStaticLists,
        HasNavigable,
        HasUuidPrimaryKey;

    const STATUS_PUBLISHED = 'published';

    const STATUS_HIDDEN = 'unpublished';

    const VOCABULARY_POST_CATEGORIES = 'post_categories';

    const VOCABULARY_FAQ_CATEGORIES = 'faq_categories';

    const VOCABULARY_PRODUCT_CATEGORIES = 'product_categories';

    const VOCABULARY_BRANDS = 'brands';

    const VOCABULARY_TAGS = 'tags';
    const VOCABULARY_PRODUCTPARITIES = 'productparities';
    const VOCABULARY_PRODUCTMODELS = 'productmodels';

    protected $attributes = [
        'weight' => 10000,
    ];

    protected $casts = [
        'added' => 'array',
        'fields' => 'array',
    ];

    protected array $mediaSingleCollections = ['image', 'logo'];

    public $translatedAttributes = ['name', 'body', 'fields'];

    protected static function booted(): void
    {
//        static::created(function (self $term) {
//            if (!$term->feed_id) {
//                do {
//                    $code = \Str::random(8);
//                } while (self::where(['domain_id' => $term->domain_id, 'feed_id' => $code])->exists());
//
//                $term->setAttribute('feed_id', $code)->saveQuietly();
//            }
//        });
    }

    /**
     * Атрибути (для категорій товарів).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function attrs()
    {
        return $this->morphedByMany(Attribute::class, 'termable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function brandProducts()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoryProducts()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * @return mixed
     */
    public function promotions()
    {
        return $this->morphToMany(Promotion::class, 'model', 'promoables')->isActive();
    }

    /**
     * @return mixed
     */
    public function faqs()
    {
        return $this->hasMany(Post::class, 'category_id')
            ->where('type', Post::TYPE_FAQ);
    }

    public function getBreadcrumbs(): array
    {
        $terms = $this->ancestors;

        $res = [];
        /** @var Term $term */
        foreach ($terms as $term) {
            $res[] = $term->only('id', 'slug', 'name', 'status') + ['model' => 'term'];
        }

        return $res;
    }

    public function scopeByAllowed(Builder $builder)
    {
        // TODO
        //$builder->where('status', self::STATUS_PUBLISHED)
        //->where(fn($b) => $b->whereJsonContains('locales', app()->getLocale()));
    }

    public function checkAllowed()
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            abort(404);
        }

        return $this;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function vocabulariesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'slug' => 'product_categories',
                'name' => 'Категорії товарів',
                'has_hierarchy' => false,
                'fa_icon' => 'fa-cubes',
            ],
//            [
//                'slug' => 'post_categories',
//                'name' => 'Категорії статей',
//                'has_hierarchy' => \Domain::getOpt("terms.vocabularies.post_categories.has_hierarchy"),
//                'fa_icon' => 'fa-file-picture-o',
//                'permissions' => ['create' => Gate::check('post.create'), 'read' => Gate::check('post.read'), 'update' => Gate::check('post.update'), 'delete' => Gate::check('post.delete')],
//            ],
//            [
//                'slug' => 'faq_categories',
//                'name' => 'Категорії FAQ',
//                'has_hierarchy' => \Domain::getOpt("terms.vocabularies.faq_categories.has_hierarchy"),
//                'fa_icon' => 'fa-hands-helping',
//                'permissions' => ['create' => Gate::check('faq.create'), 'read' => Gate::check('faq.read'), 'update' => Gate::check('faq.update'), 'delete' => Gate::check('faq.delete')],
//            ],
//            [
//                'slug' => 'brands',
//                'name' => 'Бренди',
//                'has_hierarchy' => false,
//                'fa_icon' => 'fa-diamond',
//                'permissions' => ['create' => Gate::check('product.create'), 'read' => Gate::check('product.read'), 'update' => Gate::check('product.update'), 'delete' => Gate::check('product.delete')],
//            ],
//            [
//                'slug' => 'tags',
//                'name' => 'Tags',
//                'has_hierarchy' => false,
//                'fa_icon' => 'fa-tags',
//                'permissions' => ['create' => Gate::any(['post.create', 'product.create']), 'read' => Gate::any(['post.read', 'product.read']), 'update' => Gate::any(['post.update', 'product.update']), 'delete' => Gate::any(['post.delete', 'product.delete'])],
//            ],
//            [
//                'slug' => 'productparities',
//                'name' => 'Види парності',
//                'has_hierarchy' => false,
//                'fa_icon' => 'fa-tags',
//                'permissions' => ['create' => Gate::check('product.create'), 'read' => Gate::check('product.read'), 'update' => Gate::check('product.update'), 'delete' => Gate::check('product.delete')],
//            ],
//            [
//                'slug' => 'productmodels',
//                'name' => 'Моделі товарів',
//                'has_hierarchy' => false,
//                'fa_icon' => 'fa-tags',
//                'permissions' => ['create' => Gate::check('product.create'), 'read' => Gate::check('product.read'), 'update' => Gate::check('product.update'), 'delete' => Gate::check('product.delete')],
//            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * @return string
     */
    public function getVocabularyStr(string $column = 'name', $key = 'slug'): string
    {
        return self::vocabulariesList($column, $key)[$this->vocabulary] ?? '';
    }

    public function getCategoryPathStr( $delimiter = '>'): string
    {
        $res = '';
        foreach ($this->ancestors as $item) {
            $res .= $item->name . $delimiter;
        }

        $res .= $this->name ?? '';

        return  $res;
    }

    public function getSelectFeed()
    {
        if (empty($this->google_merchant_id)) {
            return null;
        }

        $feeds = (new GoogleMerchant)->getCategories(['q' => $this->google_merchant_id]);

        return count($feeds) ? [
            $feeds[0]['id'] => $feeds[0]['name'],
        ] : null;
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
    public function getStatus(string $column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->status] ?? null;
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
     * @param array $params
     * @return string
     */
    public function getUrlClient($params = []): string
    {
        $params = is_array($params) ? $params : [$params];
        $params = array_merge([$this], $params);

        if ($this->vocabulary === Term::VOCABULARY_BRANDS) {
            return route('catalog.brands.show', $params);
        }
        elseif ($this->vocabulary === Term::VOCABULARY_POST_CATEGORIES) {
            return route('blog.category', $params);
        }
        elseif ($this->vocabulary === Term::VOCABULARY_TAGS) {
            return route('blog.tag', $params);
        }

        return route('catalog.category', $params);
    }

    /**
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser ?: strip_tags(substr($this->body ?: '', 0, 150));
    }

    /**
     * @param string|null $group
     * @return array
     */
    public function getSeoTags(?string $group = null): array
    {
        \StrToken::setEntities(['term' => $this]);

        $patterns = $this->getSeoPatterns();

        $res = $this->getRawSeoTags(\Domain::getLocale()) ?: $patterns;
        $res['og_image'] = $this->getMyFirstMediaUrl('image', 'og_image');

        foreach ($res as $key => $value) {
            $value = $value ?: Arr::get($patterns, $key, '');
            if ($value && is_string($value)) {
                $res[$key] = \StrToken::setText($value)->replace();
            }
        }

        return $res;
    }

    /**
     * @return array
     */
    public function getSeoPatterns(): array
    {
        return \Variable::getArray("seo.patterns.{$this->vocabulary}", [], \Domain::getGroup());
    }

    /**
     * TODO: deprecated!!!
     * Використоано в адмінці /admin/suggest/terms?vocabulary=product_categories
     *
     * @param Builder $builder
     * @param string $vocabulary
     * @param string|null $search
     * @param int|null $limit
     * @param string|null $ancestorId
     * @param string|null $parenId
     * @param string|null $locale
     * @return void
     */
    public function scopeSearchByVocabulary(
        Builder $builder,
        string  $vocabulary = null,
        string  $search = null,
        int     $limit = null,
        string  $ancestorId = null,
        string  $parenId = null,
        ?string $locale = null
    ) {
        $locale = $locale ?: app()->getLocale();

        $builder
            ->when($vocabulary <> null, fn($q) => $q->byVocabulary($vocabulary))
            ->when($ancestorId <> null, fn($query2) => $query2->whereDescendantOf($ancestorId))
            ->when($parenId <> null, fn($query2) => $query2->where('parent_id', $parenId == 0 ? null : $parenId));

        $search = !empty($search) ? mb_strtolower($search) : null;

        //$builder->when($search <> null, fn($query2) => $query2->where('name', 'LIKE', "%$search%"))
        $builder->when($search <> null, fn($q) => $q->where("index->q_{$locale}", 'LIKE', "%{$search}%"));
        $builder->when($limit <> null, fn($query2) => $query2->limit($limit));
    }

    public function scopeFilterable(Builder $builder, array $params = [], array $default = [])
    {
        $f = ($params ?: request()->all()) + $default;
        $locale = Arr::get($f, 'locale', \app()->getLocale());

        $search = !empty($f['q']) ? mb_strtolower($f['q']) : null;
        $builder->when($search, fn($q) => $q->where("index->q_{$locale}", 'LIKE', "%{$search}%"));

        $builder->when(Arr::get($f, 'parent_id') === 0 || Arr::get($f, 'parent_id') === '0', fn($b) => $b->whereIsRoot());
        $builder->when($val = Arr::get($f, 'parent_id'), fn($b) => $b->whereParentId($val));

        $builder->when($val = Arr::get($f, 'ids') ?? Arr::get($f, 'id'), fn ($q) => $q->whereIn('id', Arr::wrap($val)));
        $builder->when($val = Arr::get($f, 'limit'), fn ($q) => $q->limit($val));
    }
}
