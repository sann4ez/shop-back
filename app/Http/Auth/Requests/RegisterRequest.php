<?php

namespace App\Http\Auth\Requests;

use App\Http\FormRequest;
use App\Models\User;
use App\Rules\Phone;
use App\Rules\UniqueModel;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        $res = [
            'email' => ['required', 'email:strict', 'max:255', new UniqueModel(User::class)],
            'phone' => ['sometimes', 'unique:users,phone', new Phone()],
            'name' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'password' => ['required', 'string', Password::min(8), 'max:255'],
            'accept' => 'sometimes|accepted',
            'role' => ['sometimes', Rule::in(User::allowedRegisterRoles())]
        ];

        if ($this->has('password_confirmation')) {
            $res['password'] = ['required', 'string', Password::min(8), 'max:255', 'confirmed'];
        }

        return $res;
    }

    public function prepareForValidation()
    {
        $this->prepareForValidationPhoneValues('phone');
    }
}
