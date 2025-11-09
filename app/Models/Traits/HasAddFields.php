<?php

namespace App\Models\Traits;

use Illuminate\Support\Arr;

trait HasAddFields
{
    public function getFields(string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->fields ?: [];
        }

        return Arr::get($this->fields ?: [], $key) ?? $default;
    }

    public function getFieldsSort(string $key, $default = null)
    {
        $items = $this->getFields($key, $default);

        if (\is_array($items)) {
            usort($items, function ($item1, $item2) {
                return ($item1['weight'] ?? 0) <=> ($item2['weight'] ?? 0);
            });

            return $items;
        }

        return $items;
    }

    public function getPrepareFields($key = null, $default = null)
    {
        if ($key === false) {
            return null;
        }

        if (is_null($key)) {
            return $this->prepareStaticFields($this->fields ?: []);
        }

        if (is_array($key)) {
            return Arr::only($this->fields ?: [], $key) ?? $default;
        }

        return Arr::get($this->fields ?: [], $key) ?? $default;
    }

    protected function prepareStaticFields(array $fields = [])
    {
        $res = $fields;

        foreach ($fields as $key => $val) {
            if (is_array($val)) {
                $res[$key] = $this->prepareStaticFields($val);
            } elseif (is_string($val)) {
                $res[$key] = get_lfm_image_cache($val);
            }
        }

        return $res;
    }

    public static function prepareFieldsForSave(array $fieldsRaw = [], $notarrays = null): array
    {
        $fields = $fieldsRaw;

        if ($notarrays) {
            $notarrays = is_array($notarrays)
                ? $notarrays
                : explode(',', $notarrays ?? '');

            foreach ($fieldsRaw as $key => $value) {
                $fields[$key] = is_array($value) && !in_array($key, $notarrays) ? array_values($value) : $value;
            }
        }

        return $fields;
    }
}
