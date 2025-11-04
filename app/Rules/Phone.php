<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class Phone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): void  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value && !str_starts_with($value, '+')) {
            $value = '+' . $value;
        }

        $rule = new \Propaganistas\LaravelPhone\Rules\Phone();

        $data = [];
        Arr::set($data, $attribute, $value);

        $validator = Validator::make($data, [
            $attribute => [$rule],
        ]);

        if ($validator->fails()) {
            $fail($validator->errors()->first($attribute));
        }
    }
}
