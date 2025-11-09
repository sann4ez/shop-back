<?php

namespace App\Http\Admin\Requests;

use App\Rules\Phone;

final class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->user()?->id;

        $rules = [
            'name' => 'required|string|max:100',
            'lastname' => 'nullable|string|max:100',
            'middlename' => 'nullable|string|max:100',
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $id],
            'phone' => ['nullable', 'unique:users,phone,' . $id, new Phone()],
            'login' => ['sometimes', 'string', 'max:255', 'unique:users,login,' . $id],
        ];

        if ($this->input('password')) {
            $rules['password'] = 'required|string|min:8|max:255|confirmed';
        }

        return $rules;
    }

    public function passedValidation()
    {
        if ($this->phone) {
            $this->merge(['phone' => trim($this->phone, '+')]);
        }
    }

    public function prepareForValidation(): void
    {
        $this->prepareForValidationPhoneValues(['phone'], true);
    }
}
