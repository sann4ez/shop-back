<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Ordersending;
use App\Models\Traits\HasStaticLists;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory,
        HasUuids,
        HasStaticLists;

    const DEFAULT_ROLE_ADMIN = 'admin';
    const DEFAULT_ROLE_CLIENT = 'client';

    const ROLE_MANAGER = 'manager';
    const ROLE_ADMIN = 'admin';

    const ROLE_CLIENT = 'client';

    protected $casts = [
        'modules' => 'array',
        'added' => 'array',
        'is_personal' => 'boolean',
    ];

    /**
     * Групи сповіщень.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @param array $options
     * @return array
     */
    public static function notifyGroupsList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
            ['key' => 'user', 'name' => 'Користувач', 'tokens' => \App\Models\User::tokensList('name', 'key')],
            ['key' => 'order', 'name' => 'Замовлення', 'tokens' => \App\Models\Order::tokensList('name', 'key')],
            ['key' => 'ordersending', 'name' => 'Відправлення', 'tokens' => array_merge(\App\Models\Ordersending::tokensList('name', 'key'), \App\Models\Shop\Order::tokensList('name', 'key'))],
//            ['key' => 'lead', 'name' => 'Лід', 'tokens' => \App\Models\Lead::tokensList('name', 'key')],
//            ['key' => 'comment', 'name' => 'Коментар', 'tokens' => \App\Models\Extern\Comment::tokensList('name', 'key')],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * Типи каналів сповіщень.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @param array $options
     * @return array
     */
    public static function notifyChannelsList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
            [
                'key' => 'mail',
                'name' => 'Email',
                'fields' => [
                    ['name' => 'subject', 'label' => 'Subject', 'type' => 'text', 'class' => 'mail-subject'],
                    ['name' => 'body', 'label' => 'Body', 'type' => 'textarea', 'rows' => 8, 'class' => 'mail-body'],
                ],
            ],
            [
                'key' => 'messenger',
                'name' => 'Messenger',
                'fields' => [
                    ['name' => 'body', 'label' => '', 'type' => 'textarea', 'rows' => 4,],
                ],
            ],
            [
                'key' => 'settings',
                'name' => '',
                'fields' => [
                    ['name' => 'delay', 'label' => 'Delay, min', 'type' => 'number', 'default' => 0],
                ],
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    /**
     * Список подій.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @param array $options
     * @return array
     */
    public static function eventsList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
            [
                'key' => 'UserCreated',
                'name' => 'Користувач створено (адміном чи при замовленні)',
                'group' => 'user',
                'settings' => ['delay'],
            ],
            [
                'key' => 'UserRegistered',
                'name' => 'Користувач почав реєстрацію (відправити код верифікації, якщо вкл.)',
                'group' => 'user',
                'settings' => ['delay'],
                'off' => false,
            ],
            [
                'key' => 'UserRegisteredFinish',
                'name' => 'Користувач завершив реєстрацію (і верифікацію, якщо вкл.)',
                'group' => 'user',
                'settings' => ['delay'],
            ],
            [
                'key' => 'UserPasswordReset',
                'name' => 'Відновлення пароля. Відправка коду',
                'group' => 'user',
                'settings' => ['delay'],
            ],
            [
                'key' => 'UserOtp',
                'name' => 'Авторизація через код (OTP)',
                'group' => 'user',
                'settings' => ['delay'],
            ],

            [
                'key' => 'OrderOrdered',
                'name' => 'Замовлення оформлено (checkout)',
                'group' => 'order',
                'settings' => ['delay'],
            ],
            [
                'key' => 'OrderPaid',
                'name' => 'Замовлення оплачено (online)',
                'group' => 'order',
                'settings' => ['delay'],
            ],
        ];

        foreach (Order::statusesList() as $status) {
            $records[] = [
                'key' => 'OrderStatusChanged' . ucfirst($status['key']),
                'name' => "Замовлення: {$status['name']}",
                'group' => 'order',
                'settings' => ['delay'],
            ];
        }

        $records[] = [
            'key' => 'OrdersendingGetNumber',
            'name' => 'Відправлення отримало ТТН',
            'group' => 'ordersending',
            'settings' => ['delay'],
        ];
        foreach (Ordersending::statusesList() as $status) {
            if ($status['key'] === Ordersending::STATUS_FORMED) {
                continue;
            }
            $records[] = [
                'key' => 'OrdersendingStatusChanged' . ucfirst($status['key']),
                'name' => "Відправлення: {$status['name']}",
                'group' => 'ordersending',
                'settings' => ['delay'],
            ];
        }

        foreach (Lead::formsList() as $form) {
            $records[] = [
                'key' => 'LeadAdded' . ucfirst($form['key']),
                'name' => "Лід: {$form['name']}",
                'group' => 'lead',
                'settings' => ['delay'],
            ];
        }

        $records[] = [
            'key' => 'CommentAdded',
            'name' => 'Коментар додано',
            'group' => 'comment',
            'settings' => ['delay'],
        ];

        $records = self::notifyFilterGroup($records, $options);

        $records = array_filter($records, function ($el) {
           return empty(Arr::get($el, 'off'));
        });

        $records = array_filter($records, function ($el) {
           return !in_array($el['key'], \Domain::getOpt('notifications.except', []));
        });

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    private static function notifyFilterGroup(array $records, array $options): array
    {
        if ($group = Arr::get($options, 'group')) {
            $records2 = [];
            foreach ($records as $record) {
                if (Arr::get($record, 'group') === $group) {
                    $records2[] = $record;
                }
            }
            $records = $records2;
        }

        return $records;
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
}
