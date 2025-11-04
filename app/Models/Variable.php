<?php

namespace App\Models;

use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasUuidPrimaryKey;

class Variable extends \Fomvasss\Variable\Models\Variable
{
    use HasUuidPrimaryKey, HasStaticLists;

    public static function sectionsList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
            [
                'title' => 'Загальне',
                'key' => 'common',
                'fa_icon' => 'fa fa-cog',
                'perm' => '',
            ],
            [
                'title' => 'Домен',
                'key' => 'domain',
                'fa_icon' => 'fa fa-circle',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Сайт',
                'key' => 'site',
                'fa_icon' => 'fab fa-chrome',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Магазин',
                'key' => 'shop',
                'fa_icon' => 'fas fa-cart-arrow-down',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Сповіщення',
                'key' => 'notify',
                'fa_icon' => 'far fa-bell',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Клієнтські сповіщення',
                'key' => 'notifyclient',
                'fa_icon' => 'far fa-bell',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Менеджерські сповіщення',
                'key' => 'notifymanager',
                'fa_icon' => 'far fa-bell',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Платіжні системи',
                'key' => 'payments',
                'fa_icon' => 'fas fa-credit-card',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Платіжні системи ∞',
                'key' => 'payments2',
                'fa_icon' => 'fas fa-credit-card',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Доставки',
                'key' => 'shipping',
                'fa_icon' => 'fa fa-truck',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Доставки ∞',
                'key' => 'shipping2',
                'fa_icon' => 'fa fa-truck',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Списки',
                'key' => 'items',
                'fa_icon' => 'fas fa-list',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Checkbox',
                'key' => 'checkbox',
                'fa_icon' => 'fa fa-check',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Rozetka',
                'key' => 'rozetka',
                'fa_icon' => 'fas fa-plug',
                'perm' => 'settings.content',
            ],
//            [
//                'title' => 'Socialite',
//                'key' => 'socialite',
//                'fa_icon' => 'fab fa-github'
//            ],
            [
                'title' => 'SEO',
                'key' => 'seo',
                'fa_icon' => 'fas fa-chart-line',
                'perm' => ['settings.content', 'seo.manage'],
            ],
            [
                'title' => 'Профілізація',
                'key' => 'profiling',
                'fa_icon' => 'fas fa-paw',
                'perm' => 'settings.content',
            ],

            [
                'title' => 'Адмінпанель',
                'key' => 'dashboard',
                'fa_icon' => 'fas fa-palette',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Сервер',
                'key' => 'server',
                'fa_icon' => 'fas fa-palette',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Mail',
                'key' => 'mail',
                'fa_icon' => 'fas fa-envelope-square',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Файли (LFM)',
                'key' => 'lfm-file',
                'fa_icon' => 'fas fa-paste',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Фото (LFM)',
                'key' => 'lfm-image',
                'fa_icon' => 'fas fa-images',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Переклади',
                'key' => 'translations',
                'fa_icon' => 'fas fa-language',
                'perm' => 'settings.content',
            ],
            [
                'title' => 'Інформація',
                'key' => 'info',
                'fa_icon' => 'fas fa-info',
                'perm' => 'settings.system',
            ],
            [
                'title' => 'Системні логи',
                'key' => 'logs',
                'fa_icon' => 'fas fa-book',
                'perm' => 'settings.system',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey, array_merge( ['only' => \Domain::getOpt('settings.sections', [])], $options));
    }


    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function tokensList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => '[var:contact:phone]',
                'name' => 'Contact Phone',
            ],
            [
                'key' => '[var:contact:email]',
                'name' => 'Contact Email',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    public static function strTokensValues(): array
    {
        return [
            'name' => config('app.name'),
        ];
    }
}
