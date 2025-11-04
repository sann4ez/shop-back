<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('array_search_assoc')) {
    /**
     * Знайти асоціативний елемент в асоц. масиві.
     *
     * @param array $needle
     * @param array $haystack
     * @param $returnItem
     * @return false|int|mixed|string
     */
    function array_search_assoc(array $needle, array $haystack, $returnItem = false)
    {
        $keys = array_keys($needle);
        foreach ($haystack as $n => $item) {
            $break = false;
            foreach ($keys as $key) {
                if ($item[$key] !== $needle[$key]) {
                    $break = true;
                    break;
                }
            }
            if (!$break) {
                return $returnItem ? $item : $n;
            }
        }

        return false;
    }
}

if (! function_exists('array_sort_assoc')) {
    /**
     * Сортує масив асоціативних масивів за вказаним ключем.
     *
     * @param array $array Масив, який потрібно відсортувати
     * @param string $key Ключ, за яким сортувати
     * @param string $direction Напрямок сортування: 'asc' або 'desc'
     * @return array Відсортований масив
     */
    function array_sort_assoc(array $array, string $key, string $direction = 'asc'): array
    {
        usort($array, function ($a, $b) use ($key, $direction) {
            $valueA = $a[$key] ?? null;
            $valueB = $b[$key] ?? null;

            if ($valueA == $valueB) return 0;

            if ($direction === 'asc') {
                return ($valueA < $valueB) ? -1 : 1;
            } else {
                return ($valueA > $valueB) ? -1 : 1;
            }
        });

        return $array;
    }
}

if (! function_exists('array_unset_value')) {
    /**
     * Видалити елемент масиву по значенню.
     *
     * @param array $array
     * @param $val
     * @return array
     */
    function array_unset_value(array $array, $val): array
    {
        $key = array_search($val, $array);

        if ($key !== false) {
            unset($array[$key]);
        }

        return $array;
    }
}

if (! function_exists('url_add_params')) {
    /**
     * Доповнити URL GET-параметрами.
     *
     * @param string $url
     * @param array $paramsToAdd
     * @return string
     */
    function url_add_params(string $url, array $paramsToAdd = [])
    {
        if ($paramsToAdd === []) {
            return $url;
        }

        // Розділяємо URL на шлях та GET-параметри
        $urlParts = parse_url($url);

        // Визначаємо протокол (якщо відсутній)
        $protocol = isset($urlParts['scheme']) ? $urlParts['scheme'] . '://' : 'https://';

        // Визначаємо шлях
        $path = isset($urlParts['path']) ? $urlParts['path'] : '';

        // Визначаємо GET-параметри
        $query = isset($urlParts['query']) ? $urlParts['query'] : '';

        // Розбиваємо наявні GET-параметри на масив
        parse_str($query, $existingParams);

        // Об'єднуємо наявні та додаткові GET-параметри
        $combinedParams = array_merge($existingParams, $paramsToAdd);

        // Перетворюємо масив параметрів в рядок
        $newQuery = http_build_query($combinedParams);

        // Збираємо оновлений URL
        $newUrl = $protocol . $urlParts['host'] . $path . '?' . $newQuery;

        return $newUrl;
    }
}


if (! function_exists('pluralize_ukrainian')) {
    /**
     * @param string $url
     * @param array $paramsToAdd
     * @return string
     */
    function pluralize_ukrainian(int $number, array $forms = [])
    {
        $cases = [2, 0, 1, 1, 1, 2];

        return $number . ' ' . $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }
}

if (! function_exists('escape_markdown')) {
    /**
     * @param $text
     * @return array|string|string[]
     */
    function escape_markdown(string $text): string
    {
        // Символи, які потрібно екранувати
        $specialCharacters = ['\\', '_', '*', '`', '[', ']', '(', ')', '~', '#', '+', '-', '.', '!'];

        // Екрануємо кожен спеціальний символ
        foreach ($specialCharacters as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }

        return $text;
    }
}

if (! function_exists('is_valid_uuid')) {
    /**
     * @param $text
     * @return bool
     */
    function is_valid_uuid(string $text): bool
    {
        return is_scalar($text) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $text);
    }
}


if (! function_exists('get_lfm_image_cache')) {
    /**
     * @param $text
     * @return bool
     */
    function get_lfm_image_cache(string $url): string
    {
        $pos = strpos($url, 'photos');

        if (preg_match('/\.(jpeg|jpg|png|gif)$/', $url) && $pos !== false) {
            $path = substr($url, $pos);
            $fullPath = storage_path("app/public/{$path}");
            $sharesIndex = strpos($fullPath, 'shares/');
            $startIndex = $sharesIndex + strlen('shares/');
            $relativePath = substr($fullPath, $startIndex) . '.webp';
            $cacheFile = md5($relativePath) . '.webp';

            if (Storage::disk('imagecache')->exists($cacheFile)) {
                return Storage::disk('imagecache')->url($cacheFile);
            }

            if (Storage::disk('public')->exists($path)) {
                return route('imagecache', $relativePath);
            }
        }

        return $url;
    }
}

if (! function_exists('telegram_clean_html')) {
    /**
     * @param string $html
     * @return string
     */
    function telegram_clean_html(string $html): string
    {
        $html = str_replace(['<br>', '<br/>', '<br />'], "\n", $html);
        $html = str_replace(['<p>', '</p>'], "\n", $html);

        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'b,strong,i,em,a[href],code,pre,blockquote');
        $purifier = new \HTMLPurifier($config);

        $cleanHtml = $purifier->purify($html);

        return $cleanHtml;
    }
}

if (! function_exists('filter_explode')) {
    /**
     * @param $val
     * @return array
     */
    function filter_explode($val = null): array
    {
        if (empty($val)) {
            return [];
        }

        if (is_string($val)) {
            return explode(',', $val);
        }

        if (is_array($val)) {
            return $val;
        }

        return [];
    }
}

if (!function_exists('url_build_with_date_range')) {
    /**
     * @param $from
     * @param $to
     * @return string
     */
    function url_build_with_date_range($from, $to):string {
        $currentParams = request()->query();
        $currentParams['created_at_from'] = $from->format('Y-m-d');
        $currentParams['created_at_to'] = $to->format('Y-m-d');

        return url()->current() . '?' . http_build_query($currentParams);
    }
}

if (!function_exists('url_clear')) {
    function url_clear(string $str) {
        $res = preg_replace('#<a[^>]*>(.*?)</a>#is', '$1', $str);
        $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@";

        return preg_replace($regex, ' ', $res);
    }
}

if (!function_exists('hex_to_rgba')) {
    function hex_to_rgba($hex, $alpha = 1): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba($r, $g, $b, $alpha)";
    }
}


if (!function_exists('datetime_to_client')) {
    function datetime_to_client($datetime = null, $format = null, $default = null)
    {

        if (empty($datetime)) {
            return $default;
        }

        $clientTZ = config('app.timezone_client') ?: config('app.timezone');

        $date = $datetime instanceof \DateTime
            ? $datetime
            : \Carbon\Carbon::parse($datetime);

        $res = $date->timezone($clientTZ);

        if ($res && $format) {
            return $res->format($format);
        }

        return $res;

    }
}

if (! function_exists('get_path_without_host')) {
    /**
     * @param string $str
     * @param bool $withQuery
     * @return string
     */
    function get_path_without_host(mixed $str, bool $withQuery = false): string
    {
        $path = parse_url(strval($str), PHP_URL_PATH);

        if (!($path === '/')) {
            $path = trim($path, '/\\');
        }

        if ($withQuery && ($query = parse_url(strval($str), PHP_URL_QUERY))) {
            $path = $path . '?' . $query;
        }

        return $path;
    }
}

if (! function_exists('array_transform_str_items')) {
    /**
     * @param array $items
     * @param string $trimChars
     * @param string $encoding
     * @return array
     */
    function array_transform_str_items(array $items, string $trimChars = '', string $encoding = 'UTF-8'): array
    {
        return array_map(function ($item) use ($trimChars, $encoding) {
            return transform_str_item($item, $trimChars, $encoding);
        }, $items);
    }
}

if (! function_exists('transform_str_item')) {
    /**
     * @param string $item
     * @param string $trimChars
     * @param string $encoding
     * @return string
     */
    function transform_str_item(string $item, string $trimChars = '', string $encoding = 'UTF-8'): string
    {
        // trim з вказаними символами
        $trimmed = $trimChars ? trim($item, $trimChars) : trim($item);

        // ucfirst з підтримкою UTF-8
        $firstChar = mb_substr($trimmed, 0, 1, $encoding);
        $rest = mb_substr($trimmed, 1, null, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $rest;
    }
}

if (! function_exists('str_to_float')) {
    /**
     * @param string $str
     * @return float
     */
    function str_to_float(string $str): float
    {
        return (float) preg_replace('/[^\d.]/', '', str_replace(',', '.', $str));
    }
}

if (! function_exists('calculate_reading_time')) {
    /**
     * Підрахунок часу читання у хвилинах
     *
     * @param string $text
     * @param int $readingSpeedChars
     * @param bool $stripHtml
     * @return int
     */
    function calculate_reading_time(string $text, int $readingSpeedChars = 1500, bool $stripHtml = true): int
    {
        if ($stripHtml) {
            $text = strip_tags($text);
        }

        $charCount = \Illuminate\Support\Str::length($text);

        $minutes = (int) ceil($charCount / $readingSpeedChars);

        return $minutes;
    }
}

if (! function_exists('calc_volume')) {
    /**
     * Обраховуємо об'єм для відправки.
     *
     * @param float $width Ширина
     * @param float $height Висота
     * @param float $length Довжина
     * @return float
     */
    function calc_volume(float $width, float $height, float $length): float
    {
        return round($width * $height * $length / 1000000, 4);
    }
}

if (! function_exists('calc_ordersending_gabarites')) {
    /**
     * Обраховуємо параметри габаритів для відправки.
     *
     * @param array $params Масив параметрів.
     * @return array
     */
    function calc_ordersending_gabarites(array $params): array
    {
        $totalVolume = 0;
        $totalWeight = 0;

        foreach ($params as $box) {
            $totalVolume += $box['width'] * $box['height'] * $box['length'];
            $totalWeight += $box['weight'];
        }

        // Знаходимо середні співвідношення сторін
        $avgWidth = array_sum(array_column($params, 'width')) / (count($params) ?: 1);
        $avgHeight = array_sum(array_column($params, 'height')) / (count($params) ?: 1);
        $avgLength = array_sum(array_column($params, 'length')) / (count($params) ?: 1);

        // Масштабуємо розміри, щоб отримати той самий загальний об'єм
        $scaleFactor = pow($totalVolume / (($avgWidth * $avgHeight * $avgLength) ?: 1), 1/3);

        $finalWidth = round($avgWidth * $scaleFactor);
        $finalHeight = round($avgHeight * $scaleFactor);
        $finalLength = round($avgLength * $scaleFactor);

        return [
            'width' => $finalWidth ?: 15, // 15 - мінімальні значення для нової пошти
            'height' => $finalHeight ?: 15,
            'length' => $finalLength ?: 15,
            'weight' => $totalWeight ?: 1,
        ];
    }
}

if (! function_exists('build_robots')) {
    /**
     * Формує строку для meta robots з масиву
     *
     * @param array|null $robotses
     * @param string $default
     * @return string
     */
    function build_robots(?array $robotses, string $default = 'noindex,nofollow'): string
    {
        if (empty($robotses)) {
            return $default;
        }

        return collect($robotses)
            ->filter(fn($value) => $value == "1")
            ->keys()
            ->implode(', ') ?: $default;
    }
}



if (! function_exists('number_to_words')) {
    /**
     * Формує строку для meta robots з масиву
     *
     * @param array|null $robotses
     * @param string $default
     * @return string
     */
    function number_to_words(float $number): string
    {
        $hryvnia = floor($number);
        $kopiyky = round(($number - $hryvnia) * 100);

        $formatter = new \NumberFormatter('uk', \NumberFormatter::SPELLOUT);
        $hryvniaWords = $formatter->format($hryvnia);

        if ($kopiyky > 0) {
            $kopiykyWords = $formatter->format($kopiyky);

            return "{$hryvniaWords} гривень {$kopiykyWords} копійок";
        }

        return "{$hryvniaWords} гривень";
    }
}

if (! function_exists('array_merge_recursive_strategy')) {
    /**
     * Рекурсивне злиття масивів з вибором стратегії
     *
     * @param array $array1
     * @param array $array2
     * @param string $strategy
     *     - "overwrite"  → значення з $array2 перезаписують $array1
     *     - "merge"      → як array_merge_recursive (дублює значення)
     *     - "unique"     → як merge, але без дублювання елементів
     *
     * @return array
     */
    function array_merge_recursive_strategy(array $array1, array $array2, string $strategy = 'unique'): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = array_merge_recursive_strategy($merged[$key], $value, $strategy);
            } elseif (is_numeric($key)) {
                if ($strategy === 'merge') {
                    $merged[] = $value;
                } elseif ($strategy === 'unique') {
                    if (!in_array($value, $merged, true)) {
                        $merged[] = $value;
                    }
                } elseif ($strategy === 'overwrite') {
                    $merged[] = $value;
                }
            } else {
                if ($strategy === 'merge' && isset($merged[$key])) {
                    $merged[$key] = [$merged[$key], $value];
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }
}

if (! function_exists('encode_url_path')) {
    /**
     * Кодує path частину URL (для кирилиці, пробілів тощо)
     *
     * @example
     *  encode_url_path('https://site.com/шлях/з пробілами.jpg');
     *  => https://site.com/%D1%88%D0%BB%D1%8F%D1%85/%D0%B7%20%D0%BF%D1%80%D0%BE%D0%B1%D1%96%D0%BB%D0%B0%D0%BC%D0%B8.jpg
     */
    function encode_url_path(string $url = ''): string
    {
        if (empty($url)) {
            return '';
        }

        $parts = parse_url($url);

        if (! isset($parts['scheme'], $parts['host'])) {
            return $url; // некоректний URL
        }

        // кодуємо лише path
        $encodedPath = '';
        if (isset($parts['path'])) {
            $segments = explode('/', $parts['path']);
            $encodedPath = implode('/', array_map('rawurlencode', $segments));
        }

        // збираємо назад
        $newUrl = "{$parts['scheme']}://{$parts['host']}{$encodedPath}";
        if (isset($parts['query'])) {
            $newUrl .= "?{$parts['query']}";
        }

        return $newUrl;
    }
}
