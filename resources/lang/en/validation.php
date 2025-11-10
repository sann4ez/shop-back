<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Рядки мови для валідації
    |--------------------------------------------------------------------------
    |
    | Наступні рядки містять стандартні повідомлення про помилки, які
    | використовуються класом валідатора. Деякі з цих правил мають
    | кілька варіантів, наприклад, правила для розмірів.
    |
    */

    'accepted' => 'Поле :attribute повинно бути прийняте.',
    'active_url' => 'Поле :attribute не є дійсним URL.',
    'after' => 'Поле :attribute повинно бути датою після :date.',
    'after_or_equal' => 'Поле :attribute повинно бути датою після або рівною :date.',
    'alpha' => 'Поле :attribute може містити лише літери.',
    'alpha_dash' => 'Поле :attribute може містити лише літери, цифри, дефіси та підкреслення.',
    'alpha_num' => 'Поле :attribute може містити лише літери та цифри.',
    'array' => 'Поле :attribute повинно бути масивом.',
    'before' => 'Поле :attribute повинно бути датою до :date.',
    'before_or_equal' => 'Поле :attribute повинно бути датою до або рівною :date.',
    'between' => [
        'numeric' => 'Поле :attribute повинно бути між :min та :max.',
        'file' => 'Розмір файлу :attribute повинен бути між :min та :max кілобайт.',
        'string' => 'Довжина :attribute повинна бути між :min та :max символів.',
        'array' => 'Поле :attribute повинно містити від :min до :max елементів.',
    ],
    'boolean' => 'Поле :attribute повинно бути істинним або хибним.',
    'confirmed' => 'Підтвердження поля :attribute не співпадає.',
    'date' => 'Поле :attribute не є дійсною датою.',
    'date_equals' => 'Поле :attribute повинно бути датою, рівною :date.',
    'date_format' => 'Поле :attribute не відповідає формату :format.',
    'different' => 'Поля :attribute та :other повинні відрізнятись.',
    'digits' => 'Поле :attribute повинно містити :digits цифр.',
    'digits_between' => 'Поле :attribute повинно містити від :min до :max цифр.',
    'dimensions' => 'Зображення :attribute має недійсні розміри.',
    'distinct' => 'Поле :attribute має дубльоване значення.',
    'email' => 'Поле :attribute повинно бути дійсною електронною адресою.',
    'email_blocked' => 'Заборонена російська електронна адреса.',
    'ends_with' => 'Поле :attribute повинно закінчуватись одним із наступних значень: :values.',
    'exists' => 'Вибране значення для :attribute недійсне.',
    'file' => 'Поле :attribute повинно бути файлом.',
    'filled' => 'Поле :attribute повинно мати значення.',
    'gt' => [
        'numeric' => 'Поле :attribute повинно бути більше ніж :value.',
        'file' => 'Файл :attribute повинен бути більший за :value кілобайт.',
        'string' => 'Поле :attribute повинно містити більше ніж :value символів.',
        'array' => 'Поле :attribute повинно містити більше ніж :value елементів.',
    ],
    'gte' => [
        'numeric' => 'Поле :attribute повинно бути не менше ніж :value.',
        'file' => 'Файл :attribute повинен бути не менше ніж :value кілобайт.',
        'string' => 'Поле :attribute повинно містити не менше ніж :value символів.',
        'array' => 'Поле :attribute повинно містити щонайменше :value елементів.',
    ],
    'image' => 'Поле :attribute повинно бути зображенням.',
    'in' => 'Вибране значення для :attribute недійсне.',
    'in_array' => 'Поле :attribute не існує у :other.',
    'integer' => 'Поле :attribute повинно бути цілим числом.',
    'ip' => 'Поле :attribute повинно бути дійсною IP-адресою.',
    'ipv4' => 'Поле :attribute повинно бути дійсною IPv4-адресою.',
    'ipv6' => 'Поле :attribute повинно бути дійсною IPv6-адресою.',
    'json' => 'Поле :attribute повинно бути дійсним JSON-рядком.',
    'lt' => [
        'numeric' => 'Поле :attribute повинно бути менше ніж :value.',
        'file' => 'Файл :attribute повинен бути менше ніж :value кілобайт.',
        'string' => 'Поле :attribute повинно містити менше ніж :value символів.',
        'array' => 'Поле :attribute повинно містити менше ніж :value елементів.',
    ],
    'lte' => [
        'numeric' => 'Поле :attribute повинно бути не більше ніж :value.',
        'file' => 'Файл :attribute повинен бути не більше ніж :value кілобайт.',
        'string' => 'Поле :attribute повинно містити не більше ніж :value символів.',
        'array' => 'Поле :attribute не може містити більше ніж :value елементів.',
    ],
    'max' => [
        'numeric' => 'Поле :attribute не може бути більше ніж :max.',
        'file' => 'Файл :attribute не може бути більше ніж :max кілобайт.',
        'string' => 'Поле :attribute не може містити більше ніж :max символів.',
        'array' => 'Поле :attribute не може містити більше ніж :max елементів.',
    ],
    'mimes' => 'Поле :attribute повинно бути файлом одного з типів: :values.',
    'mimetypes' => 'Поле :attribute повинно бути файлом одного з типів: :values.',
    'min' => [
        'numeric' => 'Поле :attribute повинно бути не менше ніж :min.',
        'file' => 'Файл :attribute повинен бути не менше ніж :min кілобайт.',
        'string' => 'Поле :attribute повинно містити щонайменше :min символів.',
        'array' => 'Поле :attribute повинно містити щонайменше :min елементів.',
    ],
    'multiple_of' => 'Поле :attribute повинно бути кратним :value.',
    'not_in' => 'Вибране значення для :attribute недійсне.',
    'not_regex' => 'Формат поля :attribute недійсний.',
    'numeric' => 'Поле :attribute повинно бути числом.',
    'password' => 'Неправильний пароль.',
    'present' => 'Поле :attribute повинно бути присутнім.',
    'regex' => 'Формат поля :attribute недійсний.',
    'required' => 'Поле :attribute є обов’язковим.',
    'required_if' => 'Поле :attribute є обов’язковим, коли :other дорівнює :value.',
    'required_unless' => 'Поле :attribute є обов’язковим.',
    'required_with' => 'Поле :attribute є обов’язковим.',
    'required_with_all' => 'Поле :attribute є обов’язковим.',
    'required_without' => 'Поле :attribute є обов’язковим.',
    'required_without_all' => 'Поле :attribute є обов’язковим.',
    'prohibited' => 'Поле :attribute заборонене.',
    'prohibited_if' => 'Поле :attribute заборонене, коли :other дорівнює :value.',
    'prohibited_unless' => 'Поле :attribute заборонене, якщо :other не входить у :values.',
    'same' => 'Поля :attribute і :other повинні співпадати.',
    'size' => [
        'numeric' => 'Поле :attribute повинно дорівнювати :size.',
        'file' => 'Файл :attribute повинен бути розміром :size кілобайт.',
        'string' => 'Поле :attribute повинно містити :size символів.',
        'array' => 'Поле :attribute повинно містити :size елементів.',
    ],
    'starts_with' => 'Поле :attribute повинно починатися з одного з наступних значень: :values.',
    'string' => 'Поле :attribute повинно бути рядком.',
    'timezone' => 'Поле :attribute повинно бути дійсною часовою зоною.',
    'unique' => 'Значення поля :attribute вже використовується.',
    'uploaded' => 'Не вдалося завантажити :attribute.',
    'url' => 'Формат поля :attribute недійсний.',
    'uuid' => 'Поле :attribute повинно бути дійсним UUID.',

    /*
    |--------------------------------------------------------------------------
    | Користувацькі повідомлення валідації
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'користувацьке повідомлення',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Користувацькі атрибути
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'ім’я',
        'firstname' => 'ім’я',
        'lastname' => 'прізвище',
        'middlename' => 'по батькові',
        'fullname' => 'повне ім’я',

        'email' => 'електронна адреса',
        'phone' => 'телефон',
        'username' => 'логін',
        'password' => 'пароль',
        'password_confirmation' => 'підтвердження пароля',

        'title' => 'заголовок',
        'slug' => 'посилання (slug)',
        'description' => 'опис',
        'content' => 'контент',
        'text' => 'текст',
        'message' => 'повідомлення',
        'comment' => 'коментар',

        'image' => 'зображення',
        'photo' => 'фото',
        'avatar' => 'аватар',
        'file' => 'файл',
        'document' => 'документ',

        'category' => 'категорія',
        'subcategory' => 'підкатегорія',
        'tag' => 'тег',

        'price' => 'ціна',
        'amount' => 'сума',
        'quantity' => 'кількість',
        'total' => 'загальна сума',

        'address' => 'адреса',
        'city' => 'місто',
        'region' => 'регіон',
        'country' => 'країна',
        'zip' => 'поштовий індекс',

        'date' => 'дата',
        'time' => 'час',
        'birthday' => 'дата народження',

        'status' => 'статус',
        'role' => 'роль',
        'permissions' => 'права доступу',

        'url' => 'посилання',
        'link' => 'посилання',

        'agreement' => 'угода',
        'terms' => 'умови використання',
        'captcha' => 'перевірка CAPTCHA',

        'login' => 'логін',
    ],

    'phone' => 'Поле :attribute має невірний формат.',
    'Failed captcha validation' => 'Перевірка CAPTCHA не пройдена.',
];
