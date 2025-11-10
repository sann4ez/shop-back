<?php

namespace App\Models;
use App\Models\Role;
//use App\Models\Order;
//use App\Models\ProductVariation;
use App\Models\Traits\UserTrait;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Fomvasss\MediaLibraryExtension\HasMedia\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory,
        HasStaticLists,
        HasDatetimeFormatterTz,
        InteractsWithMedia,
        Notifiable,
        UserTrait,
//        UserTokens,
        HasUuidPrimaryKey,
        HasApiTokens,
        HasRoles;

    const ROLE_GUEST = 'guest';
    const ROLE_CLIENT = 'client';
    const ROLE_ADMIN = 'admin';


    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCKED = 'blocked';

    const SOURCE_REGISTERED = 'registered';
    const SOURCE_ORDER = 'order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var int
     */
    protected $perPage = 100;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'birthday' => 'datetime',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'registered_at' => 'datetime',
        'activity_at' => 'datetime',
        'added' => 'array',
        'notifies' => 'array',
        'channels' => 'array',
        'contacts' => 'array',
        'fields' => 'array',
        'discount' => 'integer',
        'birthday' => 'datetime',
    ];

    /**
     * @var string[]
     */
    protected $attributes = [
        'role' => self::ROLE_CLIENT,
        'status' => self::STATUS_ACTIVE,
    ];

    protected array $mediaSingleCollections = ['avatar'];

    /**
     * Socialite мережі.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialites()
    {
        return $this->hasMany(Socialite::class);
    }

    /**
     * Всі замовлення & корзини.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Оформлені замовлення.
     *
     * @return mixed
     */
    public function ordersOrdered()
    {
        return $this->orders()->whereType(Order::TYPE_ORDER)->whereNotNull('ordered_at');
    }

    /**
     * Коди верифікації, реєстрації...
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usercodes()
    {
        return $this->hasMany(Usercode::class);
    }

    /**
     * Обрані.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    /**
     * Список статусів.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function statusesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => self::STATUS_ACTIVE,
                'name' => 'Активний',
            ],
            [
                'key' => self::STATUS_BLOCKED,
                'name' => 'Заблокований',
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
        return self::statusesList($column, 'key')[$this->status] ?? '';
    }

    /**
     * Список можливих ролей.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @param array $options
     * @return array
     */
    public static function rolesList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = Role::select('name')->get()->map(fn($r) => ['name' => Str::studly($r->name), 'key' => $r->name])->toArray();

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    /**
     * Користувачі, яким буде надсилатися notification при вказаному event по їхнім roles.
     * https://i.imgur.com/Pus10uL.png
     *
     * @param $event
     * @param User|null $client
     * @return array
     */
    public static function getNotifiableUsersByEvent($event, User|null $client = null): array
    {
        $usersByRole = [];

        // Персональні сповіщення - https://ibb.co/49D1sF2
        if ($client) {
            $personalRoles = Role::query()
                ->where('is_personal', true)
                ->whereJsonContains('added->events', is_string($event) ? $event : class_basename($event))
                ->get();

            if ($personalRoles->isNotEmpty() && $client->hasAnyRole($personalRoles)) {
                foreach ($personalRoles as $role) {
                    // Не дістаємо всіх користувачів із персональною роллю, а беремо клієнта якщо він передається
                    if (in_array($role->name, $client->getRoleNames()->toArray())) {
                        $usersByRole[$role->name] = collect([$client]);
                    }
                }
            } else {
                if ($personalRoles->where('name', Role::ROLE_CLIENT)->first()) {
                    $usersByRole[Role::ROLE_CLIENT] = collect([$client]);
                }
            }
        }

        // Сповіщення для всіх
        $roles = Role::query()
            ->where('is_personal', false)
            ->whereJsonContains('added->events', is_string($event) ? $event : class_basename($event))
            ->get();

        foreach ($roles as $role) {
            // На всякий випадок, якщо не проставлять - https://ibb.co/49D1sF2 для клієнта
            if ($role->name === Role::ROLE_CLIENT) {
                continue;
            }

            $users = self::role($role->name)
                ->byNotDev()
                ->where('status', User::STATUS_ACTIVE)
                ->get();

            if ($users->isNotEmpty()) {
                $usersByRole[$role->name] = $users;
            }
        }

        return $usersByRole;
    }

    /**
     * Канали сповіщень, увімкнені у користувача.
     * @return array
     */
    public function getChannels(): array
    {
        return $this->channels ?: [];
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeByNotDev(Builder $builder)
    {
        $emails = config('auth.dev_user');

        if ($email = auth()->user()?->email) {
            $emails = array_unset_value($emails, $email);
        }

        $builder->when($emails, fn($b) => $b->where(fn($b2) => $b2->whereNotIn('email', $emails)->orWhereNull('email')));
    }

    /**
     * @return mixed
     */
    public function routeNotificationForTelegram()
    {
        return $this->telegram_id;
    }

    /**
     * @return mixed
     */
    public function routeNotificationForTurboSms()
    {
        return $this->phone;
    }

    /**
     * Канал broadcasting для Notifications.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return "user.{$this->id}.notifications";
    }

    /**
     * @return Attribute
     */
    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->lastname . ' ' . $this->name . ' ' . $this->middlename),
        );
    }

    /**
     * Ролі, дозволені при реєстрації.
     *
     * @return string[]
     */
    public static function allowedRegisterRoles(): array
    {
        return [self::ROLE_GUEST, self::ROLE_CLIENT];
    }

    /**
     * Пароль для акаунту.
     *
     * @return string
     */
    public static function passwordGenerate(): string
    {
        return Str::random(8);
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function toggleFavorite(Model $model): string
    {
        if ($favorite = $this->favorites->where('model_id', $model->id)->first()) {
            $favorite->delete();

            return 'deleted';
        }

        $this->favorites()->create(['model_id' => $model->id, 'model_type' => $model->getMorphClass()]);

        return 'added';
    }

    public function favoriteVariations()
    {
        return $this->favorites()
            ->where('model_type', (new ProductVariation())->getMorphClass());
    }

    public function favoritePosts()
    {
        return $this->favorites()
            ->where('model_type', (new Post())->getMorphClass());
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
     * @param string|null $key
     * @param $default
     * @return array|\ArrayAccess|mixed
     */
    public function getFields(string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->fields ?? [];
        }

        return Arr::get($this->fields ?? [], $key, $default);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isRegistered()
    {
        return (bool) $this->registered_at;
    }

    /**
     * Знижка юзеру на прродукцію відносно початкової ціни.
     *
     * @param float $price
     * @return float
     */
    public function calcDiscountPrice(float $price): float
    {

        if ($price && $this->discount) {
            return $price - ($price - $this->discount * $price / 100);
        }

        return $price;
    }

    /**
     * @param Builder $builder
     * @param array $attrs
     * @param array $default
     */
    public function scopeFilterable(Builder $builder, array $attrs = [], array $default = [])
    {
        $attrs = ($attrs ?: request()->all()) + $default;

        $builder->when($val = Arr::get($attrs, 'q'), fn($b) => $b->where(fn($q2) => $q2->whereAny(['name', 'lastname', 'middlename', 'email', 'phone'], 'LIKE', "%$val%")));

        $builder->when($val = filter_explode(Arr::get($attrs, 'ids')), fn($q) => $q->whereIn('id', $val));
        $builder->when($val = filter_explode(Arr::get($attrs, 'id')), fn($q) => $q->whereIn('id', $val));
        $builder->when($val = Arr::get($attrs, 'name'), fn($b) => $b->where('name', 'LIKE', "%$val%"));
        $builder->when($val = Arr::get($attrs, 'email'), fn($b) => $b->where('email', 'LIKE', "%$val%"));
        $builder->when($val = Arr::get($attrs, 'status'), fn($b) => $b->whereIn('status', Arr::wrap($val)));
        //$builder->when($val = Arr::get($attrs, 'role'), fn($b) => $b->whereIn('role', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'role'), fn($b) => $b->whereHas('roles', fn($r) => $r->whereIn('name', Arr::wrap($val))));

        if ($state = Arr::get($attrs, 'state')) {
            $builder->when($state === 'old', fn($q) => $q->whereHas('orders', fn ($q) => $q->whereIn('perform', [Order::PERFORM_DONE, Order::PERFORM_CONFIRMED]))
                ->withCount(['orders' => fn ($q) => $q->whereIn('perform', [Order::PERFORM_DONE, Order::PERFORM_CONFIRMED])])
                ->having('orders_count', '>', 1))
                ->when($state === 'new', fn($q) => $q->withCount('orders')
                    ->having('orders_count', '=', 1)
                    ->whereHas('orders', fn ($q) => $q->whereIn('perform', [Order::PERFORM_DONE, Order::PERFORM_CONFIRMED])));
        }

        $appTZ = config('app.timezone');

        if ($val = Arr::get($attrs, 'created_at_from')) {
            $builder->whereDate('created_at', '>=', Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'created_at_to')) {
            $builder->whereDate('created_at', '<=', Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        if ($val = Arr::get($attrs, 'registered_at_from')) {
            $builder->whereDate('registered_at', '>=', Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'registered_at_to')) {
            $builder->whereDate('registered_at', '<=', Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        if ($val = Arr::get($attrs, 'activity')) {
            $builder->when($val === 'has', fn($b) => $b->whereNotNull('activity_at'), fn($b) => $b->whereNull('activity_at'));
        }

        if ($val = Arr::get($attrs, 'activity_at_from')) {
            $builder->whereDate('activity_at', '>=', \Carbon\Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'activity_at_to')) {
            $builder->whereDate('activity_at', '<=', Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        // Sort
        if ($sort = Arr::get($attrs, 'sort')) {
            $order = Arr::get($attrs, 'order');
            $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
            if (in_array($sort, ['name', 'email', 'phone', 'status', 'created_at', 'activity_at', 'registered_at'])) {
                $builder->orderBy($sort, $order);
            } elseif ($sort === 'fullname') {
                $builder->orderByRaw("CONCAT_WS(' ', `lastname`, `name`, `middlename`) {$order}");
            } else {
                $builder->latest();
            }
        } else {
            $builder->latest();
        }

        // Limit
        $val = Arr::get($attrs, 'limit') ?: Arr::get($default, 'limit');
        $builder->when($val, fn($b) => $b->limit($val));
    }
}
