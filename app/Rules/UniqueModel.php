<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueModel implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected string $modelClass,
        protected ?string $attribute = null,
        protected ?string $ignoreId = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $attribute = $this->attribute ?: $attribute;

        $query = $this->modelClass::where($attribute, $value);

        if ($this->ignoreId) {
            $query->where('id', '<>', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail(trans('validation.unique', ['attribute' => $attribute]));
        }
    }
}
