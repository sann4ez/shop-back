<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory,
        HasDatetimeFormatterTz,
        HasStaticLists,
        HasUuidPrimaryKey;

    protected $guarded = ['id'];

    // Шлюз - платіжна система
    const GATEWAY_FONDY = 'fondy';
    const GATEWAY_PAYPAL = 'paypal';
    const GATEWAY_LIQPAY = 'liqpay';
    const GATEWAY_STRIPE = 'stripe';
    const GATEWAY_WAYFORPAY = 'wayforpay';
    const GATEWAY_MONOBANK = 'monobank';
    const GATEWAY_NOVAPAY = 'novapay';
    const GATEWAY_CASH = 'cash';
    const GATEWAY_PAYCARD = 'paycard';
    const GATEWAY_REQUISITE = 'requisite';

    const GATEWAY_CHECKBOX = 'checkbox';

    //const GATEWAY_RECEIVED = 'received'; // TODO received

    // Ручная оплата
    const METHOD_MANUAL = 'manual';             // ручна оплата
    const METHOD_RECURRING = 'recurring';       // рекурентна оплата

    const STATUS_PENDING = 'creating';     // Очікує оплати

    const STATUS_PAID = 'paid';             // Успішно оплачено
    const STATUS_FAILED = 'failed';         // Платіж не вдався
    const STATUS_CANCELED = 'canceled';     // Платіж відмінено
    const STATUS_RETURNED = 'returned';     // Повернення платужу

    // Назначение
    const PURPOSE_SUBS = 'subscription';

    const OPERATION_INCOME = 'income';
    const OPERATION_EXPENSE = 'expense';

    protected $attributes = [
        'status' => self::STATUS_PENDING,
        'method' => self::METHOD_MANUAL,
        'operation' => self::OPERATION_INCOME,
        'currency_code' => 'UAH'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'fiscal_at' => 'datetime',
        'payment_url_expires_at' => 'datetime',
        'added' => 'array',
        'is_guarantee' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function (Model $model) {
            if ($model->status !== self::STATUS_PENDING && empty($model->paid_at)) {
                $model->setAttribute('paid_at', now());
            } elseif ($model->status === self::STATUS_PENDING) {
                $model->setAttribute('paid_at', null);
            }
        });

        static::saved(function (Model $model) {
            $order = $model->model;
            if ($order) {
                $order->setAttribute('profit', $order->getProfitSum());
                $order->saveQuietly();
            }
        });

        static::creating(function (Model $model) {
            $order = $model->model;
            if ($order && $order instanceof Order) {
                $n = $order->payments->count()  + 1;
                $model->number = $order->number . '-' . $n;
            }
        });

        static::deleted(function (Model $model) {
            $order = $model->model;
            if ($order) {
                $order->setAttribute('profit', $order->getProfitSum());
                $order->saveQuietly();
            }
        });
    }

    /**
     * Модель, за яку платять (замовлення, підписка, оренда, і т.д.).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Платник.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Доступно зміна статусу платежу.
     *
     * @return bool
     */
    public function canStatusChanged(): bool
    {
        // якщо онлайн і уже оплатчено
        if ($this->payment_url && $this->status === self::STATUS_PAID) {
            return false;
        }

        if ($this->gateway === \App\Models\Payment::GATEWAY_CHECKBOX) {
            return false;
        }

        // якщо ін. платіж, наприклад з checkbox
        if (!in_array($this->gateway, [\App\Models\Payment::GATEWAY_WAYFORPAY, \App\Models\Payment::GATEWAY_MONOBANK, \App\Models\Payment::GATEWAY_REQUISITE,])) {
            return false;
        }

        return true;
    }

    /**
     * Можна видалити платіж.
     *
     * @return bool
     */
    public function canDeleted(): bool
    {
        if (!$this->canStatusChanged()) {
            return false;
        }

        if ($this->status === self::STATUS_PAID) {
            return false;
        }

        return true;
    }

    /**
     * Чи оплачено платіж.
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Пробуємо отримати посилання на оплату без генерації на стороні платіжної системи.
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        if ($this->isPaid()) {
            /** @var Order $order */
            $order = $this->model;

            $url = route('payment.info', ['info' => 'progress', 'order_number' => $order->number]);

            return $url;
        }

        // Перевіряємо, чи не закінчився термін оплати
        if ($this->payment_url_expires_at?->isFuture()) {
            return $this->payment_url;
        }

        return null;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function operationsList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => self::OPERATION_INCOME,
                'name' => 'Надходження',
                'bcolor' => '#28a745',
                'color' => '#fff',
            ],
            [
                'key' => self::OPERATION_EXPENSE,
                'name' => 'Витрата',
                'bcolor' => '#f01010',
                'color' => '#fff',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    public function getOperation(string $column = 'name'): string|array|null
    {
        return self::operationsList($column, 'key')[$this->operation] ?? null;
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
                'key' => self::STATUS_PENDING,
                'name' => 'Очікує',
            ],
            [
                'key' => self::STATUS_PAID,
                'name' => 'Оплачено',
            ],
            [
                'key' => self::STATUS_FAILED,
                'name' => 'Помилка',
            ],
            [
                'key' => self::STATUS_CANCELED,
                'name' => 'Скасовано',
            ],
            [
                'key' => self::STATUS_RETURNED,
                'name' => 'Повернено',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    public function getStatus(string $column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->status] ?? null;
    }

    public static function purposesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => self::PURPOSE_SUBS,
                'name' => trans('lte::main.Subscription'),
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    public function getPurpose(string $column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->purpose] ?? null;
    }

    public static function gatewaysList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
            [
                'key' => self::GATEWAY_WAYFORPAY,
                'name' => 'WayForPay',
                'fields' => [
                    ['name' => 'title', 'label' => 'Назва', 'type' => 'text',],
                    ['name' => 'account', 'label' => 'Account', 'type' => 'text',],
                    ['name' => 'secret_key', 'label' => 'Secret key', 'type' => 'text',],
                    ['name' => 'domain_merchant', 'label' => 'Domain merchant', 'type' => 'text',],
                ],
                'fields2' => [
                    'account', 'secret_key', 'domain_merchant'
                ],
                'links' => [
                    ['name' => 'Docs', 'path' => 'https://wiki.wayforpay.com'],
                    ['name' => 'Callback', 'path' => route('webhooks.payment.notify', [self::GATEWAY_WAYFORPAY])],
                ],
                'online' => true,
            ],
            [
                'key' => self::GATEWAY_MONOBANK,
                'name' => 'Monobank',
                'fields' => [
                    ['name' => 'title', 'label' => 'Назва', 'type' => 'text',],
                    ['name' => 'fiscal', 'label' => 'Fiscal', 'type' => 'text',],
                    ['name' => 'payment_token', 'label' => 'Payment token', 'type' => 'text',],
                    ['name' => 'x_cms', 'label' => 'X cms', 'type' => 'text',],
                ],
                'fields2' => [
                    'fiscal', 'payment_token', 'x_cms',
                ],
                'links' => [
                    ['name' => 'Docs', 'path' => 'https://api.monobank.ua/docs/acquiring.html#/paths/~1api~1merchant~1invoice~1create/post'],
                    ['name' => 'Callback', 'path' => route('webhooks.payment.notify', [self::GATEWAY_MONOBANK])],
                ],
                'online' => true,
            ],
            [
                'key' => self::GATEWAY_CASH,
                'name' => 'Готівка',
                'doc' => '',
            ],
            [
                'key' => self::GATEWAY_REQUISITE,
                'name' => 'Реквізити',
                'doc' => '',
            ],
        ];

        $options = array_merge(['only' => [self::GATEWAY_WAYFORPAY, self::GATEWAY_MONOBANK, self::GATEWAY_REQUISITE]], $options);

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    public function getGateway(string $column = 'name'): string|array|null
    {
        return self::gatewaysList($column, 'key', ['added' => ['*']])[$this->gateway] ?? null;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @param array $options
     * @return array
     */
    public static function sourcesList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = Item::getList(Item::TYPE_SOURCE)->toArray();

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    public function getSource(string $column = 'name'): string|array|null
    {
        return self::sourcesList($column, 'key')[$this->source] ?? null;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @param array $options
     * @return array
     */
    public static function categoriesList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = Item::getList(Item::TYPE_PAYMENT_CATEGORY)->toArray();

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    public function getCategory(string $column = 'name'): string|array|null
    {
        return self::categoriesList($column, 'key')[$this->category] ?? null;
    }

    public function scopeFilterable(Builder $builder, array $attrs = [])
    {
        $attrs = $attrs ?: request()->all();

        $builder->when($val = Arr::get($attrs, 'q'), fn($q) => $q->whereHas('user', fn($q) => $q->whereAny(['name', 'lastname', 'middlename', 'email', 'phone'], 'LIKE', "%$val%"))
            ->orWhereHas('model', fn($q) => $q->where('number', $val))
            ->orWhere('comment', 'LIKE', "%{$val}%"));

        $builder->when($val = Arr::get($attrs, 'user'), fn ($b) => $b->whereHas('user', fn ($bu) => $bu->whereIn('id', Arr::wrap($val))));
        $builder->when($val = Arr::get($attrs, 'source'), fn ($b) => $b->whereIn('source', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'category'), fn ($b) => $b->whereIn('category', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'model'), fn ($b) => $b->whereHas('model', fn ($bu) => $bu->whereIn('id', Arr::wrap($val))));
        $builder->when($val = Arr::get($attrs, 'operation'), fn ($b) => $b->whereIn('operation', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'status'), fn ($b) => $b->whereIn('status', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'gateway'), fn ($b) => $b->whereIn('gateway', Arr::wrap($val)));

        $builder->when($val = Arr::get($attrs, 'amount_from'), fn ($b) => $b->whereRaw('ABS(amount) >= ?', [$val]));
        $builder->when($val = Arr::get($attrs, 'amount_to'), fn ($b) => $b->whereRaw('ABS(amount) <= ?', [$val]));

        $appTZ = config('app.timezone');

        if ($paidAtStr = Arr::get($attrs, 'paid_at_between')) {
            $now = now();

            switch ($paidAtStr) {
                case 'month':
                    $from = $now->clone()->startOfMonth();
                    $to = $now->clone()->endOfMonth();
                    break;
                case 'week':
                    $from = $now->clone()->startOfWeek();
                    $to = $now->clone()->endOfWeek();
                    break;
                case 'last_year':
                    $from = $now->clone()->subYear()->startOfYear();
                    $to = $now->clone()->subYear()->endOfYear();
                    break;
                case 'last_month':
                    $from = $now->clone()->subMonth()->startOfMonth();
                    $to = $now->clone()->subMonth()->endOfMonth();
                    break;
                case 'last_week':
                    $from = $now->clone()->subWeek()->startOfWeek();
                    $to = $now->clone()->subWeek()->endOfWeek();
                    break;
                case 'year':
                default:
                    $from = $now->clone()->startOfYear();
                    $to = $now->clone()->endOfYear();
                break;
            }

            $builder->whereDate('paid_at', '>=', \Illuminate\Support\Carbon::parse($from)->startOfDay()->setTimezone($appTZ));
            $builder->whereDate('paid_at', '<=', \Illuminate\Support\Carbon::parse($to)->endOfDay()->setTimezone($appTZ));
        }

        $builder->when($val = Arr::get($attrs, 'created_at_from'), fn ($b) => $b->whereDate('created_at', '>=', \Illuminate\Support\Carbon::parse($val)->startOfDay()->setTimezone($appTZ)));
        $builder->when($val = Arr::get($attrs, 'created_at_to'), fn ($b) => $b->whereDate('created_at', '<=', \Illuminate\Support\Carbon::parse($val)->endOfDay()->setTimezone($appTZ)));

        $builder->when($val = Arr::get($attrs, 'paid_at_from'), fn ($b) => $b->whereDate('paid_at', '>=', \Illuminate\Support\Carbon::parse($val)->startOfDay()->setTimezone($appTZ)));
        $builder->when($val = Arr::get($attrs, 'paid_at_to'), fn ($b) => $b->whereDate('paid_at', '<=', \Illuminate\Support\Carbon::parse($val)->endOfDay()->setTimezone($appTZ)));

        // Sort
        if ($sort = Arr::get($attrs, 'sort')) {
            $order = Arr::get($attrs, 'order');
            $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
            if (in_array($sort, ['created_at', 'paid_at', 'status'])) {
                $builder->orderBy($sort, $order);
            } elseif (in_array($sort, ['amount'])) {
                $builder->orderByRaw('ABS(CAST(amount AS DECIMAL(10, 2))) ' . $order);
            } else {
                $builder->latest();
            }
        } else {
            $builder->latest();
        }
    }
}
