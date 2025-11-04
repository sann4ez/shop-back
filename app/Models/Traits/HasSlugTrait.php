<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait HasSlugTrait
{
    /** @var string[] Define in your model */
//    public $slugSourceColumns = ['name'];
//    public $slugColumnName = 'slug';
//    public $slugSeparator = '-';
//    public $slugMaxLength = 255;
//    public $slugGenerateIfEmptySource = true;

    protected static function bootHasSlugTrait()
    {
        static::created(function (Model $model) {
            if (empty($model->{$model->getSlugColumnName()})) {
                if (!empty($model->makeSlugRawStr()) || $model->isSlugGenerateIfEmptySource()) {
                    $model->slug = static::slugGenerate($model->makeSlugRawStr(), $model);
                    $model->saveQuietly();
                }
            }
        });

        static::updated(function (Model $model) {
            if (!empty($model->slug) || $model->isSlugGenerateIfEmptySource()) {
                $model->slug = static::slugGenerate($model->slug ?? $model->makeSlugRawStr(), $model);
                $model->saveQuietly();
            }
        });

    }

    public function getSlugSourceColumns(): array
    {
        if (empty($this->slugSourceColumns)) {
            return ['name'];
        }

        return $this->slugSourceColumns;
    }

    public function getSlugColumnName(): string
    {
        if (empty($this->slugColumnName)) {
            return 'slug';
        }

        return $this->slugColumnName;
    }

    public function getSlugSeparator(): string
    {
        if (empty($this->slugSeparator)) {
            return '-';
        }

        return $this->slugSeparator;
    }

    public function getSlugMaxLength(): int
    {
        if (empty($this->slugMaxLength)) {
            return 255;
        }

        return $this->slugMaxLength;
    }

    public function isSlugGenerateIfEmptySource(): bool
    {
        if (isset($this->slugGenerateIfEmptySource)) {
            return $this->slugGenerateIfEmptySource;
        }

        return true;
    }

    public function makeSlugRawStr(): string
    {
        return implode($this->getSlugSeparator(), $this->only($this->getSlugSourceColumns()));
    }


    /**
     * @param string $slug
     * @param Model|null $model
     * @param bool $mayNotUnique Може бути не унікальним
     * @return string
     */
    public static function slugGenerate(string $slug, ?Model $model = null, bool|null $mayUniqueSlug = true): string
    {
        $model = $model ?: new static();

        $nonUniqueSlug = static::makeNonUniqueSlug($slug, $model);

        if ($mayUniqueSlug === false) {
            return $nonUniqueSlug;
        }

        return $model->makeUniqueSlug($nonUniqueSlug, $model);
    }

    protected static function makeNonUniqueSlug(string $slug, $model): string
    {
        return Str::slug(static::getClippedSlugWithPrefixSuffix($slug, $model), $model->getSlugSeparator(), App::getLocale());
    }

    protected static function getClippedSlugWithPrefixSuffix(string $slug, $model): string
    {
        return Str::limit($slug, $model->getSlugMaxLength(), '');
    }

    protected function makeUniqueSlug(string $slug, $model): string
    {
        $originalSlug = $slug;
        $i = 1;
        while (static::otherRecordExistsWithSlug($slug, $model) || $slug === '') {
            $slug = $originalSlug . $this->getSlugSeparator() . $i++;
        }

        return $slug;
    }

    protected static function otherRecordExistsWithSlug(string $slug, $model): bool
    {
        return (bool) static::where($model->getKeyName(), '<>', $model->{$model->getKeyName()})

            ->conditionsExistsSlug($slug, $model)
            ->withoutGlobalScopes()
            ->first();
    }

    /**
     * Value uniqueness condition for a slug field.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $slug
     * @param $model
     * @return mixed
     */
    public function scopeConditionsExistsSlug($query, string $slug, $model)
    {
        // TODO
        // якщо модель має домен
        if (method_exists(self::class, 'domain')) {
            return $query->where(function ($b) use ($slug) {
                $b->where($this->getSlugColumnName(), '=', $slug)
                    ->where('domain_id', \Domain::getId());
            });
        }

        return $query->where($this->getSlugColumnName(), '=', $slug);
    }
}
