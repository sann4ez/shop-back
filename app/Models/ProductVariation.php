<?php

namespace App\Models;

use App\Actions\Products\Variations\ReindexVariationAction;
use App\Models\Traits\VariationFilter;
use App\Models\User;
use App\Models\Property;
use App\Models\Media;
use App\Models\Term;
use App\Models\Traits\InteractsWithMedia;
use App\Models\Traits\HasAddFields;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasNavigable;
use App\Models\Traits\HasProperties;
use App\Models\Traits\HasSlugTrait;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasUuidPrimaryKey;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductVariation extends Model implements HasMedia
{
    use HasFactory,
        HasDatetimeFormatterTz,
        HasUuidPrimaryKey,
        HasSlugTrait,
        HasProperties,
        HasStaticLists,
        VariationFilter,
        InteractsWithMedia,
        HasNavigable,
        HasAddFields,
        SoftDeletes;

    const STATUS_PUBLISHED = 'published';
    const STATUS_HIDDEN = 'hidden';

    const GROUPING_TYPE_ATTRIBUTE = 'attribute';
    const GROUPING_TYPE_SINGLE = 'single';
    const GROUPING_TYPE_DEFAULT = 'default';
    const GROUPING_TYPE_MAIN = 'main';
    const GROUPING_TYPE_PARITIES = 'parities';

    protected $guarded = ['id'];

    protected $casts = [
        'is_default' => 'boolean',
        'is_attribute_groped' => 'boolean',
        'added' => 'array',
        'fields' => 'array',
        'switching' => 'array',
        'barcodes' => 'string',
        'index' => 'array',
        'price' => 'float',
        'price_old' => 'float',
        'stock_qty' => 'integer',
        'income_at' => 'datetime',
        'rozetka_send' => 'boolean',
    ];

    protected $attributes = [
        'stock_qty' => 0,
    ];

    protected array $mediaMultipleCollections = ['images'];

    public array $translatedAttributes = ['name', 'body', 'fields'];

    protected string $indexName = 'variations';

    protected static function booted()
    {
        static::created(function ($model) {
            if (empty($model->product->variation)) {
                $model->update(['is_default' => true]);
            }
        });
        static::deleting(function ($model) {
            if ($model->is_default && ($variation = $model->product->variations->where('id', '<>', $model->id)->first())) {
                $variation->update(['is_default' => true]);
            }
        });
    }

    /**
     * @return int
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getSlugMaxLength(): int
    {
        if ($ln = 255) {
            return $ln;
        }

        return 30;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Акції, в який бере участь варіація.
     *
     * @return mixed
     */
    public function promotions()
    {
        return $this->morphToMany(Promotion::class, 'model', 'promoables')->isActive();
    }

    /**
     * Кількості варіацій по складам.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wareoffers()
    {
        return $this->hasMany(Wareoffer::class, 'variation_id');
    }

    public function scopeByPromotionVariations(Builder $builder, Promotion $promotion)
    {
        $promoTerms = $promotion->terms->loadTrans()->pluck('id')->toArray();

        return $builder->where(fn($q) => $q->whereIn('id', $promotion->variations->pluck('id')->toArray())
            ->when($promoTerms, function ($qq) use ($promoTerms) {
                foreach ($promoTerms as $termId) {
                    $qq->orWhereJsonContains("index->categories", $termId)
                        //->orWhereJsonContains("index->brands", $termId)
                    ;
                }
        }));
    }

    public function scopeWithCard(Builder $builder)
    {
        $builder->with([
            'media', 'translations', 'product', 'product.media', 'product.category', 'product.translations',
            'promotions', 'product.category.promotions.', 'product.brand.promotions',
        ]);
    }

    public function scopeByAllowed(Builder $builder)
    {
        return $builder->whereStatus(self::STATUS_PUBLISHED)//->where(fn($b) => $b->whereJsonContains('locales', app()->getLocale()))
            ;
    }

    /**
     * Тип групування варіації при виведенні у списках.
     *
     * @param Builder $builder
     * @param string|null $gropedType null - по замовчуванню, none - без групування
     * @return void
     */
    public function scopeByGropedType(Builder $builder, string|null $gropedType = null)
    {
        $gropedType = $gropedType ?: self::GROUPING_TYPE_DEFAULT;

        // групувати по атрибуту - де значення атрибуту різні (атрибут групування: Колір) - як на vovna
        if ($gropedType === self::GROUPING_TYPE_ATTRIBUTE) {
            $builder->where('is_attribute_groped', true);
        }
        // береться одну варіацію з групи (якщо задано тільки один атрибут, наприклад: Розмір) або ж групується як вище - attribute - як на cosset
        elseif ($gropedType === self::GROUPING_TYPE_SINGLE) {
            $builder->where('is_attribute_groped', true);
        }
        // береться одна варіація з групи - найбільш наявна або основна
        elseif ($gropedType === self::GROUPING_TYPE_MAIN) {
            $builder->where('is_attribute_groped', true);
        }
        // виводити тільки основну варіацію
        elseif ($gropedType === self::GROUPING_TYPE_DEFAULT) {
            $builder->where('is_default', true);
        }
        // виводити тільки які мають пари
        elseif ($gropedType === self::GROUPING_TYPE_PARITIES) {
            $builder->where('index->has_parities', '1')
                ->orderByRaw("CAST(`index`->'$.paritysort' AS UNSIGNED) asc");
        }
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
     * Супутні \ Зв'язані варіації.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRelateds()
    {
        if ($ids = Arr::wrap($this->getAdded('relateds'))) {
            $variations = self::whereIn('id', $ids)->with('media.model', 'product.media.model', 'product.category', 'product.category.promotions')->get();

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
        $with = ['media.model', 'product.media.model', 'product.category',
            'product.category', 'properties',
            'properties.attribute'];

        if ($ids = Arr::wrap($this->getAdded('recommends'))) {
            $variations = self::whereIn('id', $ids)
                ->byGropedType()
                ->with($with)->get();

            return $variations->sortBy(fn ($item) => array_search($item->id, $ids));
        }

        if ($orDefault && ($categoryId = $this->product?->category_id)) {
            $limit = 8;

            return self::where('id', '<>', $this->id)->whereHas('product', fn($p) => $p->where('category_id', $categoryId))
                ->byGropedType()
                ->with($with)->limit($limit)->get();
        }

        return \collect([]);
    }

    /**
     * Парні варіації до поточної.
     *
     *  В Категорії товару вказуємо Атрибути парності (Колір, Візерунок,..).
     *  В товарі вказуємо:
     *  - Категорія парності (жінкам, чоловікам, дівчатам, хлопцям)
     *  - Модель товару (Діамант, Хорватія, Маки)
     *  При виводі на фронт знайдуться і віддадуться всі варіації, в яких значення атрибутів парності (колір, візерунок) та Модель товар (якщо є) однакові, а Категорія парності - відмінна.
     *
     * @return Collection
     */
    public function getParities(): Collection
    {
        // Атрибути парності в категорії поточної варіації.
        $attrIds = $this->product->category->added['parity_attributes'] ?? [];

        // Категорії парності поточної варіації
        $productparyty = $this->product->productparity;

        $allProperties = Property::whereIn('attribute_id', $attrIds)->get()->pluck('id', 'attribute_id');

        if ($attrIds && $productparyty) {

            /** @var Builder $query */
            $query = ProductVariation::where('product_id', '<>', $this->product_id)
                ->with('translations', 'media', 'promotions', 'product.category', 'product.translations', 'product.media', 'product.productparity.translations')
            ;

            $query->where('index->productparity_id', '<>', $productparyty->id);

            if ($productModelId = $this->product->productmodel_id) {
                $query->where('index->productmodel_id', $productModelId);
            }


            // атрибути і їх значення поточної варіації
            if ($selfAttributesProperties = $this->getAttributesPropertiesListArray('id', 'id')) {
                foreach ($attrIds as $attrId) {
                    if ($prop = $selfAttributesProperties[$attrId] ?? $allProperties[$attrId] ?? null) {
                        $query->whereJsonContains("index->facet->{$attrId}", $prop);
                    }
                }

                return $query->get()->unique('product.productparity_id');
            }
        }

        return new \Illuminate\Database\Eloquent\Collection();
    }

    /**
     * ID атрибуту по якому відбувається групування в каталозі.
     *
     * @return string|null
     */
    public function getAttributeGropedId(): string|null
    {
        // цей атрибут для групування вказаний в самому товарі або в категорії товара
        return $this->product?->getAdded('attribute_groped') ?: $this->product?->category?->getAdded('attribute_groped');
    }

    /**
     * Чи має парні.
     *
     * @return bool
     */
    public function hasParities(): bool
    {
        return (bool) $this->index['has_parities'];
    }

    /**
     * Переглянуті варіації (фільтр по id з клієнта).
     *
     * @param Builder $builder
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function scopeByRevieweds(Builder $builder)
    {
        $sRevieweds = request()->get('sRevieweds') // JSON: "234,56,678,89,567"
            ?: request()->header('sRevieweds')
                ?: request()->cookie('sRevieweds');

        $reviewedArray = explode(',', $sRevieweds);

        // Баз implode не працює сортування
        $idsString = "'" . implode("','", $reviewedArray) . "'";

        $builder->whereIn('id', $reviewedArray)
            ->orderByRaw("FIELD(id, " . $idsString . ")");
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
     * @return int
     */
    public function getPerPage()
    {
        return request('limit')
            ?: request('per_page')
                ?: \session('per_page')
                    ?: $this->perPage;
    }

    /**
     * Акції прикріплені до варіації та основної категорії.
     *
     * @return Collection
     */
    public function getPromotions()
    {
        $promotions = collect([]);

        if ($this->relationLoaded('promotions')) {
            $promotions = $this->promotions;
        } elseif (app()->environment('local')) {
            //\Log::warning(__METHOD__, ['msg' => 'promotions not loaded']);
        }

        if ($this->relationLoaded('product') && $this->product?->relationLoaded('category') && $this->product->category?->relationLoaded('promotions')) {
            $promotions = $promotions->merge($this->product->category->promotions);
        } elseif (app()->environment('local')) {
            //\Log::warning(__METHOD__, ['msg' => 'product || product.category || product.category.promotions not loaded']);
        }

        if ($this->relationLoaded('product') && $this->product?->relationLoaded('brand') && $this->product->brand?->relationLoaded('promotions')) {
            $promotions = $promotions->merge($this->product->brand->promotions);
        } elseif (app()->environment('local')) {
            //\Log::warning(__METHOD__, ['msg' => 'product || product.brand || product.brand.promotions not loaded']);
        }

        return $promotions;
    }

    /**
     * URL для web-клієнта.
     *
     * @param array $params
     * @return string
     */
    public function getUrlClient($params = []): string
    {
        $domainUrl = \Domain::getSelected('url');

        if ($domainUrl === config('app.url')) { // Сайт на Blade
            if (!$this->relationLoaded('product')) {
                return '#';
            }
            if (!$this->product?->relationLoaded('category')) {
                return '#';
            }
            if (empty($this->product->category)) {
                return '#';
            }

            $params = is_array($params) ? $params : [$params];
            $params = array_merge([$this->product->category, $this], $params);

            return route('catalog.variation.show', $params);
        }

        // API сайт
        return "{$domainUrl}/{$this->slug}";
    }

    public function getAvailabilityForFeed(): string
    {
        if ($this->stock_qty > 0) {
            return 'in_stock';
        } else {
            return 'out_of_stock';
        }
    }

    /**
     * Обновити слаг варіації.
     *
     * @return bool
     */
    public function reUpdateSlug(?string $rawSlug = null)
    {
        $rawSlug = Arr::first([
            $rawSlug,
            $this->getName(),
            $this->properties->implode('value', ' '),
            $this->id,
        ] , fn ($el) => $el !== null);

        $slug = ProductVariation::slugGenerate($rawSlug, $this);

        return $this->setAttribute('slug', $slug)->saveQuietly();
    }

    /**
     * @return bool
     */
    public function isFavorite(): bool
    {
        return \Favorite::isFavorite($this);
    }

    /**
     * Чи є товар в корзині.
     *
     * @return bool|int
     */
    public function inCart(): bool|int
    {
        return \Cart::isAdded($this);
    }

    public function countCart(): int
    {
        return \Cart::countAdded($this);
    }

    /**
     * @return bool
     */
    public function isComparison(): bool
    {
        return \Comparison::isComparison($this);
    }

    /**
     * Стани моделі для клієнта.
     *
     * @return array
     */
    public function getClientStates(): array
    {
        return [
//            'is_favorite' => $this->isFavorite(),
            'in_cart' => $this->inCart(),
            'count_cart' => $this->countCart(),
//            'in_comparison' => $this->isComparison(),
        ];
    }

    /**
     * Маркери (NEW,  TOP, HOT,...)
     *
     * @return Collection
     */
    public function getMarkers(): Collection
    {
        $markers = collect();

        if ($this->relationLoaded('product') && $this->product?->relationLoaded('markers')) {
            $markers = $markers->merge($this->product->markers);
        }

        return $markers;
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
     * Назва варіації на сторінці варіації.
     *
     * @return string
     */
    public function getName(): string
    {
        $nameToken = '[variation:name]';

        return $this->makeName($nameToken);
    }

    /**
     * Назва варіації для Rozetka.
     *
     * @return string
     */
    public function getRozetkaName(): string
    {
        if ($nameToken = \Domain::getOpt('extern.rozetka.name_format', '')) {
            return $this->makeName($nameToken);
        }

        return $this->getName();
    }

    /**
     * Назва варіації в списках/каталозі.
     *
     * @return string
     */
    public function getNameList(): string
    {
        $nameToken = '[variation:name]';

        return $this->makeName($nameToken);
    }

    protected function makeName(string|null $nameToken = null): string
    {
        if (empty($nameToken)) {
            return $this->name ?: '';
        }

        switch ($nameToken) {
            case '[variation:name]':
                $res = $this->name ?: '';
                break;
            case '[product:name]':
                $res = $this->product?->name ?: '';
                break;
            case '[product:name] [variation:name]':
                $res = ($this->product?->name ?: '') . ' ' . ($this->name ?: '');
                break;
            default:
                $res = \StrToken::setEntities(['variation' => $this, 'product' => $this->product])->setText($nameToken)->replace();
        }

        return $res ?: '';
    }

    public function getTitleName()
    {
        return implode(' | ', [
            $this->getName(),
            $this->getSku(),
            $this->getBarcodesAll(),
            'Ціна:' . $this->getPrice(),
        ]);
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->getPrices('now');
    }

    /**
     * @return float
     */
    public function getPriceOld(): float
    {
        return $this->getPrices('old');
    }

    public function getBreadcrumbs(): array
    {
        $terms = [];
        if ($this->product && $this->product->category_id) {
            $terms = Term::query()->defaultOrder()->ancestorsAndSelf($this->product->category_id);
        }

        $res = [];
        /** @var Term $term */
        foreach ($terms as $term) {
            $res[] = $term->only('id', 'slug', 'name') + ['model' => 'term'];
        }

        $res[] = $this->only('id', 'slug', 'name') + ['model' => 'variation'];

        return $res;
    }

    /**
     *
     * Ціни на позицію (оригінальна, поточна, стара, акційна, знижка,..)
     *
     * @param string|null $column
     * @return array|mixed
     */
    public function getPrices(string $column = null)
    {
        $priceNow = $this->price;
        $priceOld = $this->price_old;

        $prices[] = [
            'origin' => $this->price,
            'now' => $priceNow,         // поточна/основна ціна, грн
            'old' => 0,                 // стара ціна, грн
            'discount' => 0,            // знижка, грн
            'promotion' => null,        // дані акції
            'discount_percent' => 0,    // знижка, %
            'desc' => 'Original price', // опис ціни
        ];

        if (($discountVal = $priceOld - $priceNow) > 0) {
            $prices[] = [
                'now' => $priceNow,
                'old' => $priceOld,
                'discount' => round($discountVal, 2),
//                'discount_percent' => round(($priceOld - $priceNow) * 100 / $priceOld, 2),
                'promotion' => null,
                'desc' => 'Product New/Old price',
            ];
        }

        $res = $prices[0]; // max
        foreach ($prices as $price) {
            if (($price['now'] > 0) && ($price['discount'] > $res['discount'])) {
                $res = $price;
            }
        }

        // оригінальна (raw) ціна товара
        $res['product'] = $this->price;

        return $column ? $res[$column] : $res;
    }

    /**
     * Сума/Відсоток знижки відносно старої ціни.
     *
     * @param bool $isPercent
     * @return float
     */
    public function getDiscountVal(bool $isPercent = true): float
    {
        if ($this->getPriceOld() < 0.1) {
            return 0;
        }

        if ($isPercent) {
            return round(100 - $this->getPrice() * 100 / $this->getPriceOld());
        }

        return $this->getPriceOld() - $this->getPrice();
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku ?: '';
    }

    /**
     * @return string
     */
    public function getSkuExtern(): string
    {
        return $this->sku_extern ?: '';
    }

    /**
     * Внутрішній/основний штрихкод.
     *
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->barcode ?: $this->sku ?: '';
    }

    /**
     * Ін./зовнішні штрихкоди.
     *
     * @return string
     */
    public function getBarcodes(): string
    {
        return $this->barcodes ?: '';
    }

    /**
     * Всі штрихкоди.
     *
     * @return void
     */
    public function getBarcodesAll(): string
    {
        $res = '';
        if ($this->barcode) {
            $res .= $this->barcode . ', ';
        }

        return trim($res . $this->getBarcodes(), ', ');
    }

    /**
     * Опис варіації.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body ?: $this->product->body ?? '';
    }

    /**
     * Короткий опис варіації.
     *
     * @return string
     */
    public function getTeaser(): string
    {
        $str = $this->getBody();

        return strip_tags(Str::limit( $str, 150, ''));
    }

    /**
     * Кількість доступна для покупки.
     *
     * @return mixed
     */
    public function getAvailable(): int
    {
        return $this->getQty();
    }

    /**
     * Налаштування для Seo картинок варіацій (alt...)
     *
     * @return array
     */
    public static function getImagesSeoSettings(): array
    {
        $settings = \Variable::getArray('seo.products.images', []) ?: [];

        usort($settings, fn ($a, $b) => (int) $a['weight'] <=> (int) $b['weight']);

        return $settings;
    }

    /**
     * Тип наявності позиції на складі.
     *
     * @return string
     */
    public function getAvailableStatus(): string
    {
//        if (!\Domain::getOpt('orders.use_qty_stock')) {
//            return 'unlimited';     // К-сть необмежена
//        }

        if ($this->getQty() < 1) {
            return 'missing';       //'Немає в наявності';
        }

        if ($this->getQty() < $this->getLimitQty()) {
            return 'terminate';     //'Закінчується';
        }

        return 'available';         //'В наявності';
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->stock_qty ?? 0;
    }

    /**
     * @return int
     */
    public function getLimitQty(): int
    {
        return $this->limit_qty ?? 0;
    }

    /**
     * @return int
     */
    public function getMinQty(): int
    {
        return $this->min_qty ?? 0;
    }
    /**
     * @return int
     */
    public function getMaxQty(): int
    {
        return $this->stock_qty ?? 0;
    }

    /**
     * Multiplicity
     * @return int
     */
    public function getStep(): int
    {
        return $this->multiplicity ?? 1;
    }

    /**
     * Основна категорія варіації (товару).
     *
     * @return null
     */
    public function getCategory()
    {
        if ($this->relationLoaded('product') && $this->product->relationLoaded('category')) {
            return $this->product->category;
        }

        return null;
    }

    /**
     * Рейтинг варіації (товару).
     *
     * @return float
     */
    public function getRating(): float
    {
        if ($this->relationLoaded('product')) {
            return round($this->product->getRating(), 2);
        }

        return 0;
    }

    public function getCommentsCount(): int
    {
        if ($this->relationLoaded('product')) {
            return $this->product->getCommentsCount();
        }

        return 0;
    }

    /**
     *
     * Колекція фото варіації.
     *
     * @param string $collectionName
     * @param array $filters
     * @return Collection
     */
    public function getImages(string $collectionName = 'images', $filters = []): Collection
    {
        // Фото варіації
        $res = $this->getMedia($collectionName, $filters);

        // Фото базового товара
        if ($this->relationLoaded('product') && $this->product) {

            if ($this->product->relationLoaded('media')) {
                $res = $res->merge($this->product->getMedia($collectionName, $filters));
            }

            // Фото з усіх варіацій товара
            if ($this->product->relationLoaded('variations')) {
                foreach ($this->product->variations as $variation) {

                    if ($variation->relationLoaded('media')) {
                        $res = $res->merge($variation->getMedia($collectionName, $filters));
                    }
                }
            }
        }

        return $res;
    }

    /**
     * Базова/головна/перша Media варіації.
     *
     * @param string $collectionName
     * @param $filters
     * @return Model|null
     */
    public function getImage(string $collectionName = 'images', $filters = []): Model|null
    {
        return $this->getFirstMedia($collectionName, $filters);
    }

    /**
     * @param string $collectionName
     * @param array $filters
     * @param bool $forceLoad
     * @return Media|void
     */
    public function getImageMedia(string $collectionName = 'images', $filters = [], bool $forceLoad = false)
    {
        /** @var Media $media */
        if ($media = $this->getFirstMedia($collectionName, $filters)) {
            return $media;
        }

        if (($forceLoad || $this->relationLoaded('product')) && $this->product?->relationLoaded('media')) {
            if ($media = $this->product->getFirstMedia($collectionName, $filters)) {
                return $media;
            }
        }

        return null;
    }

    /**
     *
     * Базова/головна/перша URL-фото варіації.
     *
     * @param string $collectionName
     * @param string $conversion
     * @param string $default
     * @param array $filters
     * @param bool $forceLoad
     * @return string
     */
    public function getImageUrl(string $collectionName = 'images', $conversion = '', $default = '', $filters = [], bool $forceLoad = false): string
    {
        return $this->getImageMedia($collectionName, $filters, $forceLoad)?->getFullUrl($conversion) ?? $default;
    }

    public function getImageBase64(string $collectionName = 'images', $conversion = 'thumb', $default = '', $filters = []): string
    {
        $path = $this->getImageMedia($collectionName, $filters, true)?->getPath($conversion);

        if (file_exists($path)) {
            return "data:image/png;base64,".base64_encode(file_get_contents($path));
        }

        return $default;
    }

    /**
     * ID для Розетка.
     *
     * @return string
     */
    public function getRozetkaId(): string
    {
        return $this->rozetka_id ?: $this->id;
    }

    /**
     * Характеристики товара (для таблиці на стор. товара)
     *
     * @param string $fieldAttribute
     * @param string $fieldProperty
     * @return array
     */
    public function getSpecificationsArray(string $fieldAttribute = 'slug', string $fieldProperty = 'slug'): array
    {
        $res = [];
        if ($this->relationLoaded('product')) {

            if ($this->product->relationLoaded('category')) {
                if ($this->product->category && $this->product->category->name) {
                    $res = array_merge($res, ['Category' => [$this->product->category->name]]);
                }
            }

            if ($this->product->relationLoaded('brand')) {
                if ($this->product->brand && $this->product->brand->name) {
                    $res = array_merge($res, ['Brand' => [$this->product->brand->name]]);
                }
            }

            if ($this->product->relationLoaded('properties')) {
                $res = array_merge($res, $this->product->getAttributesPropertiesListArray($fieldAttribute, $fieldProperty));
            }
        }

        return array_merge($res, $this->getAttributesPropertiesListArray($fieldAttribute, $fieldProperty));
    }

    public function getDataForIndex(): array
    {
        $this->load('product.category.promotions', 'product.markers');

        // Головна категорія
        $categoryMain = $this->product?->category;
        // Другорядні категорії
        $categoriesSecondary = $this->product?->categories ?: collect();
        // Дочірні категорії
        $categoriesParent = $categoryMain?->getAncestors() ?: collect();
        // Всі категорії
        $mergedCategories = collect($categoryMain)->concat($categoriesSecondary)->concat($categoriesParent);
        $categoriesIds = array_values(array_filter($mergedCategories->pluck('id')->toArray() ?: [], function($value) {
            return !is_null($value);
        }));

        // TODO: has_discount, sku_extern, income_at, rating, q
        return [
            'id' => $this->id,
            'name' => $this->getName(),
            'vname' => $this->name,
            'body' => $this->getBody(),
            'price' => $this->getPrice(),
            'price_max' => $this->getPrice(),
            'price_min' => $this->getPrice(),
            'stock_qty' => $this->stock_qty,
            'rating' => $this->product?->getRating(),
            'barcode' => $this->getBarcode(),
            'barcodes' => $this->getBarcodes(),
            'sku' => $this->getSku(),
            'sku_extern' => $this->getSkuExtern(),
            'status' => $this->status === ProductVariation::STATUS_PUBLISHED && $this->product?->status === Product::STATUS_PUBLISHED
                ? ProductVariation::STATUS_PUBLISHED
                : ProductVariation::STATUS_HIDDEN, // TODO метод isPublished()
            'income_at' => $this->product?->income_at,
            'created_at' => $this->created_at,
            'category_id' => $this->product?->category_id,
            'grouped_id' => $this->grouped_id,
            'categories_ids' => $categoriesIds,
            'brand_id' => $this->product?->brand_id,
            'markers_ids' => $this->getMarkers()->pluck('id')->toArray(),
            'domain_id' => $this->domain_id,
            'properties' => array_merge($this->getAttributesPropertiesListArray('id', 'id'), $this->product?->getAttributesPropertiesListArray('id', 'id')),
            'is_attribute_groped' => (bool) $this->is_attribute_groped,
            'is_default' => (bool) $this->is_default,
            'has_discount' => (bool) $this->getPriceOld() > 0.0,                          // має знижку
            'has_promotion' => (bool) $this->getPrices('promotion'),               // участь в акції
        ];
    }

    public function getMappingProperties(): array
    {
        $mappingProperties = [
            'id' => [
                'type' => 'keyword',
            ],
            'name' => [
                'type' => 'text',
                'fielddata' => true,
                'analyzer' => 'uk_ngram_analyzer',
                'search_analyzer' => 'uk_ngram_analyzer',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        'normalizer' => 'lowercase_keyword',
                    ]
                ],
            ],
            'vname' => [
                'type' => 'text',
                'fielddata' => true,
                'analyzer' => 'uk_ngram_analyzer',
                'search_analyzer' => 'uk_ngram_analyzer',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        'normalizer' => 'lowercase_keyword',
                    ]
                ],
            ],
            'body' => [
                'type' => 'text',
            ],
            'price' => [
                'type' => 'float',
            ],
            'price_max' => [
                'type' => 'float',
            ],
            'price_min' => [
                'type' => 'float',
            ],
            'stock_qty' => [
                'type' => 'integer',
            ],
            'paritysort' => [
                'type' => 'integer',
            ],
            'rating' => [
                'type' => 'float',
            ],
            'barcode' => [
                'type' => 'keyword',
            ],
            'barcodes' => [
                'type' => 'keyword',
            ],
            'sku' => [
                'type' => 'keyword',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        'normalizer' => 'lowercase_keyword',
                    ]
                ],
            ],
            'sku_extern' => [
                'type' => 'keyword',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        'normalizer' => 'lowercase_keyword',
                    ]
                ],
            ],
            'status' => [
                'type' => 'keyword',
            ],
            'income_at' => [
                'type' => 'date',
            ],
            'created_at' => [
                'type' => 'date',
            ],
            'grouped_id' => [
                'type' => 'keyword',
            ],
            'productparity_id' => [
                'type' => 'keyword',
            ],
            'productmodel_id' => [
                'type' => 'keyword',
            ],
            'category_id' => [
                'type' => 'keyword',
            ],
            'categories_ids' => [
                'type' => 'keyword',
            ],
            'brand_id' => [
                'type' => 'keyword',
            ],
            'markers_ids' => [
                'type' => 'keyword',
            ],
            'domain_id' => [
                'type' => 'keyword',
            ],
            'properties' => [
                'type' => 'object',
            ],
            'is_default' => [
                'type' => 'boolean',
            ],
            'is_attribute_groped' => [
                'type' => 'boolean',
            ],
            'has_discount' => [
                'type' => 'boolean',
            ],
            'has_promotion' => [
                'type' => 'boolean',
            ],
            'has_parities' => [
                'type' => 'boolean',
            ],
        ];

        return $mappingProperties;

    }

    public function reIndex()
    {
        ReindexVariationAction::run($this);
    }

    /**
     * Перерахувати середню ціну закупки.
     *
     * @param ProductVariation $variation
     * @return void
     */
    public function reCalcPriceCost()
    {
        /** @var Collection $varevariations */
        $varevariations = Warevariation::where('variation_id', $this->id)
            ->join('wareoperations', 'wareoperations.id', '=', 'warevariations.wareoperation_id')
            ->select('wareoperations.type', 'wareoperations.status', 'warevariations.price_cost', 'warevariations.qty')
            ->where('wareoperations.status', Wareoperation::STATUS_COMPLETED)
            ->where('wareoperations.type', Wareoperation::TYPE_POSTING)->get();

        $avgSum = $varevariations->sum(function ($operation) {
            return $operation->qty * $operation->price_cost;
        }) ?: 0;

        $price = $avgSum / ($varevariations->sum('qty') ?: 1);

        $this->setAttribute('price_cost', round($price, 2))->saveQuietly();
    }

    public static function generateValue(string $field)
    {
        if ($field === 'barcode') {
            $val = self::select(\DB::raw('MAX(CAST(barcode AS UNSIGNED)) as max_numeric_barcode'))->value('max_numeric_barcode');
            if ($val < 5000000) {
                return 5000000;
            }
            return ++$val;
        }

        $val = self::max($field) ?: 5000000;

        return ++$val;
    }

    /**
     * SEO Метатеги.
     *
     * @param string|null $group
     * @return array
     */
    public function getSeoTags(?string $group = null): array
    {
        \StrToken::setEntities(['product' => $this->product, 'variation' => $this]);

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
        return \Variable::getArray('seo.patterns.variation', [], \Domain::getGroup());
    }
}
