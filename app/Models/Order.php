<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory,
        HasUuidPrimaryKey,
        HasStaticLists,
        HasDatetimeFormatterTz,
        SoftDeletes;

    const TYPE_CART = 'cart';           // корзина
    const TYPE_ORDER = 'order';         // замовлення


    const PERFORM_PENDING = 'pending';
    const PERFORM_PENDING_RESERVED = 'pending_reserved';
    const PERFORM_CONFIRMED = 'confirmed';
    const PERFORM_DONE = 'done';
    const PERFORM_CANCELLED = 'cancelled';

    protected $guarded = ['id'];

    protected $perPage = 100;

    protected $casts = [
        'added' => 'array',
        'ordered_at' => 'datetime',
        'performed_at' => 'datetime',
        'discount_sum' => 'float',
        'delivery_sum' => 'float',
        'delivery_discount_sum' => 'float',
        'extern_data' => 'array',
    ];

    protected $attributes = [
        'type' => self::TYPE_CART,
        'perform' => self::PERFORM_PENDING,
    ];

    // TODO: Deprecated
    public $translatedAttributes = [];

    public static function booted()
    {
        self::deleting(function(self $order) {
            Log::warning(__METHOD__, [
                'msg' => 'Order deleting',
                'order'  => $order->toArray(),
                'user_id' => auth()->id(),
            ]);
            $order->payments()->each(function($payment) {
                $payment->delete();
            });
        });

        self::creating(function(self $order) {
            if (empty($order->source)) {
                $order->source = 'site';
            }
            if (empty($order->status)) {
                $order->status = 'pending';
            }
        });

        self::saving(function(self $order) {
            if (empty($order->performed_at)
                && $order->isDirty('perform')
                && in_array($order->perform, [Order::PERFORM_CONFIRMED, Order::PERFORM_DONE])) {

                $order->performed_at = now();
            }
        });
    }

    /**
     * Користувач.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Позиції замовлення.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Оплати замовлення.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'model');
    }

    /**
     * Статуси замовлення (для клієнтів).
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function statusesList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
            [
                'key' => 'pending',
                'name' => 'Очікує',
                'color' => '#C86407',
                'bg' => '#FFE9CE',
            ],
            [
                'key' => 'shipped',
                'name' => 'Відправлено',
                'color' => '#00876F',
                'bg' => '#9EFFD6',
            ],
            [
                'key' => 'done',
                'name' => 'Виконано',
                'color' => '#006516',
                'bg' => '#9EFFB9;',
            ],
            [
                'key' => 'canceled',
                'name' => 'Скасовано',
                'color' => 'white',
                'bg' => '#ff7e70',
            ],
            [
                'key' => 'declined',
                'name' => 'Відхилено',
                'color' => '#650000',
                'bg' => '#FF9E9E',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    /**
     * @return array
     */
    public static function statusesListSelectMap(): array
    {
        $res = [];
        foreach (static::statusesList('key') as $key) {
            $res[$key] = [".js-block-{$key}"];
        }

        return $res;
    }

    /**
     * Статус замовлення.
     *
     * @param string $column
     * @return string|array|null
     */
    public function getStatus(string $column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->status] ?? null;
    }

    /**
     * @return string
     */
    public function getStatusLte(): string
    {
        if ($status = $this->getStatus('*')) {
            return "<button type='button' class='btn btn-flat margin' style='background-color: {$status['bg']};color: {$status['color']}'>{$status['name']}</button>";
        }
        return '';
    }

    /**
     * Джерела, звідки замовлення.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function sourcesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => 'site',
                'name' => 'Сайт',
            ],
            [
                'key' => 'manager',
                'name' => 'Менеджер',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * @param string $column
     * @return string|array|null
     */
    public function getSource(string $column = 'name'): string|array|null
    {
        return self::sourcesList($column, 'key')[$this->source] ?? null;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function typesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => self::TYPE_ORDER,
                'name' => 'Замовлення',
            ],
            [
                'key' => self::TYPE_CART,
                'name' => 'Корзина',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * Стани виконання замовлення.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function performsList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'name' => 'Очікує',
                'key'=> self::PERFORM_PENDING,
                'desc' => 'Замовлення узгоджується з клієтном, комплектується, очікує оплати (якщо предоплата), готується до відправки та остаточного підтвердження',
                'callout' => 'warning',
                'color' => '#C86407',
                'bg' => '#ffe9ce',
                'editable' => true,
                'disableds' => ['done'],
            ],
            [
                'name' => 'Очікує (зарезервовано)',
                'key'=> self::PERFORM_PENDING_RESERVED,
                'desc' => 'Замовлення узгоджується з клієтном, комплектується, можливо оплачено, готується до відправки та остаточного підтвердження. Товари зі Складу відмінусовуються (зарезервовані за замовленням)',
                'callout' => 'warning',
                'color' => '#C86407',
                'bg' => '#ffe9ce',
                'editable' => true,
                'disableds' => ['pending', 'done'],
            ],
            [
                'name' => 'Підтверджено',
                'key'=>  self::PERFORM_CONFIRMED,
                'desc'=> 'Узгоджено з клієнтом, оплачено (якщо предоплата). Подальше редагування позицій не доступне.',
                'callout' => 'info',
                'color' => 'white',
                'bg' => '#6894f2',
                'editable' => false,
                'disableds' => ['pending', 'pending_reserved'],
            ],
            [
                'name' => 'Виконано',
                'key'=>  self::PERFORM_DONE,
                'desc' => 'Клієнт успішно отримав і оплатив. Замовлення завершено.',
                'callout' => 'success',
                'color' => '#006516',
                'bg' => '#9EFFB9;',
                'editable' => false,
                'disableds' => ['confirmed', 'pending', 'pending_reserved'],
            ],
            [
                'name' => 'Скасовано',
                'key'=>  self::PERFORM_CANCELLED,
                'desc' => 'Замовлення скасовано менеджером чи відмінено клієнтом. Якщо раніше було Підтверджено, то при скасуванні наявність товарів повернеться на Склад.',
                'callout' => 'danger',
                'color' => 'white',
                'bg' => '#ff7e70',
                'editable' => true,
                'disableds' => ['done'],
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * Виконання замовлення.
     *
     * @param string $column
     * @return string|array|null
     */
    public function getPerform(string $column = 'name'): string|array|null
    {
        return self::performsList($column, 'key')[$this->perform] ?? null;
    }

    /**
     * @return string
     */
    public function getPerformLte(): string
    {
        if ($val = $this->getPerform('*')) {
            return "<button type='button' class='btn btn-flat btn-sm margin' style='background-color: {$val['bg']};color: {$val['color']}'>{$val['name']}</button>";
        }
        return '';
    }

    /**
     * @return string
     */
    public function getRecipientLte(): string
    {
        $items = [
            $this->getAdded('recipient.lastname') . ' ' . $this->getAdded('recipient.name') . ' ' . $this->getAdded('recipient.middlename'),
            $this->getAdded('recipient.phone'),
            $this->getAdded('recipient.email'),
        ];

        $result = array_map(function($item) {
            return $item ? ('<li>' . Str::limit($item, 30) . '</li>') : '';
        }, $items);

        $result = implode('', $result);

        return "<ul class='list-unstyled small m-0'>{$result}</ul>" ;
    }

    /**
     * @param string $field
     * @return string
     */
    public function getRecipient(string $field): string
    {
        if (Arr::get($this->added, 'recipient.active')) {
            return Arr::get($this->added, "recipient.{$field}") ?: '';
        }

        return Arr::get($this->added, "user.{$field}")
            ?: Arr::get($this->added, "shipping.{$field}") // TODO: Deprecated, old version
                ?: '';
    }

    public function getRecipientData(string $shippingMethod): array
    {
        return array_merge(Arr::get($this->added, 'recipient'), Arr::get($this->added, "shipping.{$shippingMethod}") ?: []);
    }

    /**
     * @return string
     */
    public function getRecipientFullName(): string
    {
        $firstName =  Arr::get($this->added, "recipient.name") ?: '';
        $lastName = Arr::get($this->added, "recipient.lastname") ?: '';
        $middleName =  Arr::get($this->added, "recipient.middlename") ?: '';

        return $firstName.' '.$lastName.' '.$middleName;
    }

    /**
     * Отримати параметри розмірів товарів у замовленні (ширина, довжина, висота, вага)
     *
     * @return array
     */
    public function getPurchasesGabarites(): array
    {
        return collect($this->purchases)
            ->flatMap(function ($purchase) {
                $dimensions = Arr::only(optional($purchase->variation)->added ?? [], ['width', 'height', 'length', 'weight']);

                return array_fill(0, $purchase->quantity, $dimensions);
            })
            ->filter()
            ->toArray();
    }

    /**
     * @return string
     */
    public function getPaymentUrl(): string
    {
        if ($payment = $this->payments->sortByDesc('created_at')->whereNotNull('payment_url')->first()) {
            return $payment->payment_url;
        }

        return '';
    }

    /**
     * @param $key
     * @param null $default
     * @return array|\ArrayAccess|mixed
     */
    public function getAdded($key, $default = null)
    {
        return Arr::get($this->added ?? [], $key, $default);
    }

    /**
     * Список позицій замовлення.
     *
     * @return string
     */
    public function getProductListNameStr(): string
    {
        $res = [];

        foreach ($this->purchases as $purchase) {
            $res[] = $purchase->getName();
        }

        return implode(', ', $res);
    }

    /**
     * Адреса доставки рядком (для інформації).
     *
     * @return string
     */
    public function getShippingAddressStr(): string
    {
        $res = [
            $this->getAdded('shipping.country'),
            $this->getAdded('shipping.region'),
            $this->getAdded('shipping.city'),
            $this->getAdded('shipping.street'),
            $this->getAdded('shipping.house'),
            $this->getAdded('shipping.apartment'),
            $this->getAdded('shipping.zipcode'),
            $this->getAdded('shipping.warehouse'),
            $this->getAdded('shipping.custom'),
        ];

        return implode(', ', array_filter($res));
    }

    /**
     * Кількість позицій в замовленні.
     *
     * @param bool $unique
     * @return int
     */
    public function quantity(bool $unique = false): int
    {
        return $unique
            ? $this->purchases->count()
            : $this->purchases->sum('quantity');
    }

    /**
     * Кількість позицій в замовленні.
     *
     * @param bool $unique
     * @return int
     */
    public function getQty(bool $unique = false): int
    {
        return $this->quantity($unique);
    }

    /**
     * Сума цін за позиції (без врахув знижок).
     *
     * @return float
     */
    public function allPurchasesSum(): float
    {
        return round($this->purchases->sum(fn($p) => $p->price * $p->quantity), 2);
    }

    /**
     * Сума знижок для позицій.
     *
     * @return float
     */
    public function purchasesDiscountSum(): float
    {
        return round($this->purchases->sum('discount'), 2);
    }

    /**
     * Сума цін позицій (з врахув знижок).
     *
     * @return float
     */
    public function purchasesSum(bool $withoutDiscount = false): float
    {
        if ($withoutDiscount) {
            return $this->allPurchasesSum();
        }

        return $this->allPurchasesSum() - $this->purchasesDiscountSum();
    }

    /**
     * Закупівельна сума всіх продуктів.
     *
     * @return float
     */
    public function allPurchasesSumCost(): float
    {
        return round($this->purchases->sum(fn($p) => $p->price_cost * $p->quantity), 2);
    }

    /**
     * Вартість доставки.
     *
     * @param bool $withoutDiscount
     * @return float
     *
     */
    public function allDeliverySum(): float
    {
        return $this->delivery_sum ?? 0;
    }

    /**
     * Вартість доставки (зі знижкою/без знижки).
     *
     * @return float
     */
    public function deliverySum(bool $withoutDiscount = false): float
    {
        if ($withoutDiscount) {
            return $this->allDeliverySum();
        }

        return $this->allDeliverySum() - $this->deliveryDiscountSum();
    }

    /**
     * Знижка на доставку.
     *
     * @return float
     */
    public function deliveryDiscountSum(): float
    {
        return $this->delivery_discount_sum ?? 0;
    }

    /**
     * Сума загальної знижки замовлення: товари, акції, доставка...
     *
     * @return float
     */
    public function totalDiscountSum(): float
    {
        return $this->discount_sum
            + $this->purchasesDiscountSum()
            + $this->deliveryDiscountSum();
    }

    /**
     * Сума прибутку по позиціям замовлення.
     *
     * @return float
     */
    public function getProfitSum(): float
    {
        $expenseSum = $this->payments->where('operation', Payment::OPERATION_EXPENSE)->sum('amount');

        return $this->totalSum() - round($this->purchases->sum(fn($p) => $p->price_cost * $p->quantity), 2) + $expenseSum;
    }

    /**
     * Відсоток доходу.
     *
     * @return float
     */
    public function getProfitPercent(): float
    {
        if ($this->sum_cost > 0) {
            return round($this->profit * 100 / $this->sum_cost);
        } elseif ($this->sum_cost == 0) {
            return 100;
        }

        return 0;
    }

    /**
     * Колір доходу.
     *
     * @return string
     */
    public function getProfitColor(): string
    {
        $percent = $this->getProfitPercent();

        if ($percent > 0) {
            return '#0ccc00';
        } elseif ($percent == 0) {
            return '#6c757d';
        }

        return '#f4516c';
    }

    /**
     * Сума загальної знижки замовлення без товарів: акції, доставка
     *
     * @return float
     */
    public function orderDiscountSum(): float
    {
        return $this->discount_sum + $this->deliveryDiscountSum();
    }

    /**
     * TODO: Deprecated, use getTotalSum()
     *
     * @return float
     */
    public function totalSum(): float
    {
        return $this->getTotalSum();
    }

    /**
     * Загальна сума замовлення: товари, доставка, акції.
     *
     * @return float
     */
    public function getTotalSum(): float
    {
        return round($this->purchasesSum() + $this->deliverySum() - $this->discount_sum, 2);
    }

    /**
     * TODO Не використано.
     *
     * Загальна сума замовлення: товари, доставка, акції.
     *
     * @return float
     */
    public function getOriginSum(): float
    {
        return round($this->purchasesSum(true) + $this->deliverySum(true), 2);
    }

    /**
     * Різниця старої ціни товарів від поточної ціни позицій в корзині/замовленні.
     * Інформаційне значення!
     *
     * @return float
     */
    public function getDiffOldPrices(): float
    {
        return round($this->purchases->sum(function (Purchase $purchase) {
            if ($old = Arr::get($purchase->added, 'variation.prices.old')) {
                return ($old - $purchase->price) * $purchase->quantity;
            }

            return 0;
        }), 2);
    }

    /**
     * Різниця старої ціни товарів від поточної ціни позицій в корзині/замовленні.
     * Інформаційне значення!
     *
     * @return float
     */
    public function getSumOldPrices(): float
    {
        return round($this->purchases->sum(function (Purchase $purchase) {
            if ($old = Arr::get($purchase->added, 'variation.prices.old')) {
                return $old * $purchase->quantity;
            }

            return 0;
        }), 2);
    }

    /**
     * Оплачено всього.
     *
     * @return float
     */
    public function getPaidSum(): float
    {
        return $this->payments->where('status', Payment::STATUS_PAID)->sum('amount');
    }

    /**
     * Лишилося до оплати.
     *
     * @return float
     */
    public function getRemainedToPaidSum(): float
    {
        return $this->totalSum() - $this->getPaidSum();
    }

    /**
     * Загальні дані по замовленню.
     *
     * @return array
     */
    public function getTotalInfo(): array
    {
        return [
            //'currency_code' => $this->currency_code,                        // Валюта (TODO)
            'quantity' => $this->quantity(),                                // Кількість всього товарів
            'quantity_unique' => $this->quantity(true),              // Кількість унікальних одиниць товарів

            'purchases_all' => $this->allPurchasesSum(),                    // Сума за товари
            'purchases_discount' => $this->purchasesDiscountSum(),          // Знижка за товари
            'purchases' => $this->purchasesSum(),                           // Сума за товари остаточна (з врахуванням знижок)

            'delivery_all' => $this->allDeliverySum(),                      // Доставка
            'delivery_discount' => $this->deliveryDiscountSum(),            // Знижка за доставку
            'delivery' => $this->deliverySum(),                             // Доставка остаточна

            'order_discount' => round($this->discount_sum, 2),      // Знижка додаткова на замовлення (акційна + кастомна)
            'difference_old_prices' => $this->getDiffOldPrices(),           // Різниця старої ціни товарів від поточної ціни позицій в корзині/замовленні (інформаційне значення!)
            'sum_old_prices' => $this->getSumOldPrices(),                   // Сума старих цін (інформаційне значення!)

            'total_discount' => $this->totalDiscountSum(),                  // Знижка сумарна (товари + доставка + замовлення)
            'total' =>  $this->totalSum(),                                  // Сума остаточна (до оплати)
        ];
    }

    public function getTitleStr(): string
    {
        return implode(' | ', array_filter([
            $this->number,
            $this->sum,
            $this->user?->email,
            $this->user?->fullname,
        ]));
    }

    /**
     * Сторінка checkout.
     * Підтвердити оформлення замовлення.
     *
     * @param $order
     * @param array $attrs
     */
    public function doOrder(array $attrs = [])
    {
        if (empty($this->ordered_at)) {
            $this->setAttribute('ordered_at', now());
        }

        $this->setAttribute('sum', $this->totalSum());
        $this->setAttribute('sum_cost', $this->allPurchasesSumCost());
        $this->setAttribute('profit', $this->getProfitSum());

        $this->setAttribute('type', Order::TYPE_ORDER);

        $this->saveQuietly();
    }

    /**
     * Чи замовлення підтверджене/оформлене.
     *
     * @return bool
     */
    public function isOrdered(): bool
    {
        return $this->type === self::TYPE_ORDER && $this->ordered_at;
    }

    /**
     * Чи виконане / підтверджене замовлення. Товари списані зі складу.
     *
     * @return bool
     */
    public function isPerformed(): bool
    {
        return in_array($this->perform, [self::PERFORM_DONE, self::PERFORM_CONFIRMED, self::PERFORM_PENDING_RESERVED]);
    }

    /**
     * Чи безкоштовна доставка
     *
     * @return bool
     */
    public function isFreeDelivery(): bool
    {
        return $this->getAdded('is_free_delivery') ?? false;
    }

    /**
     * Оновити дані товару, покупки враховуючи позиції цього замовлення.
     *
     * @param bool $returnOrder Повернення
     * @return bool
     */
    public function refreshStockQty(bool $returnOrder = false): bool
    {
        $this->load('purchases.variation');

        /** @var Purchase $purchase */
        foreach ($this->purchases as $purchase) {
            $purchase->refreshStockQty($purchase->quantity, $returnOrder);

            // ставимо закупівельну ціну в покупку замовлення.
//            if ($returnOrder) {
//                $purchase->setAttribute('price_cost', 0);
//            } else {
//                $purchase->setAttribute('price_cost', $purchase->variation?->price_cost ?? 0);
//            }

            // ціна закупки позиції на момент покупки клієнтом
            $purchase->setAttribute('price_cost', $purchase->variation?->price_cost ?? 0);
            $purchase->saveQuietly();
        }

        return true;
    }

    /**
     * @param Builder $builder
     * @param array $attrs
     * @param array $default
     */
    public function scopeFilterable(Builder $builder, array $attrs = [], array $default = [])
    {
        $attrs = ($attrs ?: request()->all()) + $default;

        if (Arr::get($attrs, 'type') !== 'all') {
            $builder->when($val = Arr::get($attrs, 'type'), fn($b) => $b->whereIn('type', Arr::wrap($val)));
        }

        $builder->when($val = filter_explode(Arr::get($attrs, 'id')), fn($q) => $q->whereIn('id', $val));
        $builder->when($val = filter_explode(Arr::get($attrs, 'ids')), fn($q) => $q->whereIn('id', $val));
        $builder->when($val = filter_explode(Arr::get($attrs, 'user_id')), fn($q) => $q->whereIn('user_id', $val));
        $builder->when($val = Arr::get($attrs, 'source'), fn($b) => $b->whereIn('source', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'perform'), fn($b) => $b->whereIn('perform', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'status'), fn($b) => $b->whereIn('status', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'number'), fn($q) => $q->where("number", 'LIKE', '%'. $val .'%'));
        $builder->when($val = filter_explode(Arr::get($attrs, 'variation_id')), fn($q) => $q->whereHas('purchases', fn ($q) => $q->whereIn('model_id', $val)));

        // Фільтруємо замовлення в яких використаний переданий промокод (підтримується кілька)
        $builder->when(
            $val = filter_explode(Arr::get($attrs, 'promocode_id')),
                fn($q) => $q->whereHas('discounts', fn($q2) =>
                    $q2->whereHas('promocode', fn($q3) =>
                        $q3->whereIn('id', $val)))
        );

        $builder->when($val = Arr::get($attrs, 'q'), fn($b) => $b->where(fn($q2) => $q2->where('number', 'LIKE', "%{$val}%")
            ->orWhere('manager_comment', 'LIKE', "%{$val}%")
            ->orWhereHas('ordersendings', fn ($q) => $q->where('number', 'LIKE', "%{$val}%"))
        ));

        if ($state = Arr::get($attrs, 'state')) {
            $builder->when($state === 'left', fn($q) => $q
                ->where('type', Order::TYPE_CART)
                ->whereHas('purchases')
                ->where('updated_at', '<=', now()->subHours(48)));
        }


        $appTZ = config('app.timezone');

        if ($val = Arr::get($attrs, 'ordered_at_from')) {
            $builder->whereDate('ordered_at', '>=', \Illuminate\Support\Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'ordered_at_to')) {
            $builder->whereDate('ordered_at', '<=', \Illuminate\Support\Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        if ($val = Arr::get($attrs, 'created_at_from')) {
            $builder->whereDate('created_at', '>=', \Illuminate\Support\Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'created_at_to')) {
            $builder->whereDate('created_at', '<=', \Illuminate\Support\Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        if ($val = Arr::get($attrs, 'performed_at_from')) {
            $builder->whereDate('performed_at', '>=', \Illuminate\Support\Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'performed_at_to')) {
            $builder->whereDate('performed_at', '<=', \Illuminate\Support\Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        // Sort
        if ($sort = Arr::get($attrs, 'sort')) {
            $order = Arr::get($attrs, 'order');
            $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
            if (in_array($sort, ['source', 'perform', 'number', 'sum', 'sum_cost', 'profit', 'created_at', 'ordered_at'])) {
                $builder->orderBy($sort, $order);
            } else {
                $builder->latest('ordered_at')->latest('updated_at');
            }
        } else {
            $builder->latest('ordered_at')->latest('updated_at');
        }

        $val = Arr::get($attrs, 'limit') ?: Arr::get($default, 'limit');
        $builder->when($val, fn($b) => $b->limit($val));
    }
}
