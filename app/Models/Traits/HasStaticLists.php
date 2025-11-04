<?php

namespace App\Models\Traits;

use Illuminate\Support\Arr;

trait HasStaticLists
{
    protected static function staticListBuild(
        array $records = [],
        array|string $columnKey = null,
        string $indexKey = null,
        array $options = [],
    ): array {

        $allRecords = $records;

        // only - тільки наступні (крім інших умов нижче)
        if ($indexKey && ($only = $options['only'] ?? [])) {
            $only = \is_array($only) ? $only : [$only];

            // впорядкувати по only
            $res = [];
            foreach ($only as $key) {
                foreach ($records as $record) {
                    if ($record[$indexKey] === $key) {
                        $res[] = $record;
                        break;
                    }
                }
            }
            $records = $res;
        }

        // except - виключити (without - deprecated!)
        if ($without = $options['except'] ?? $options['without'] ?? []) {
            $without = Arr::wrap($without);
            $records = array_filter($records, function ($record) use ($indexKey, $without) {
                return !in_array($record[$indexKey], $without);
            });
        }

        // added - додати наступні записи
        if ($added = $options['added'] ?? []) {
            $added = Arr::wrap($added);
            if ($added === ['*']) {
                $addedRecords = $allRecords;
            } else {
                $addedRecords = array_filter($allRecords, function ($record) use ($indexKey, $added) {
                    return in_array($record[$indexKey], $added);
                });
            }

            $records = array_merge_recursive_strategy($records, $addedRecords);
        }

        // сортування
        if ($sort = $options['sort'] ?? '') {
            $order = ($options['order'] ?? 'asc') === 'asc' ? 'asc' : 'desc';
            $records = array_sort_assoc($records, $sort, $order);
        }

        if ($indexKey && $columnKey) {
            if ($columnKey === '*' || $columnKey === ['*']) {
                return array_combine(array_column($records, $indexKey), $records);
            }

            if (is_array($columnKey)) {
                $recordsRes = array_map(fn($el) => Arr::only($el, $columnKey), $records);
                return array_combine(array_column($records, $indexKey), $recordsRes);
            }

            return array_column($records, $columnKey, $indexKey);
        }

        elseif (is_scalar($columnKey)) {
            return array_column($records, $columnKey);
        } elseif (is_array($columnKey)) {
            return array_map(fn($el) => Arr::only($el, $columnKey), $records);
        }

        return $records;
    }
}
