<?php

namespace App\Http\Client\Requests;

use App\Http\FormRequest;
use App\Models\User;
use App\Rules\Phone;
use App\Rules\UniqueModel;
use Illuminate\Validation\Rules\Password;

final class ProfileUpdateRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->user()?->id;

        $res = [
            'name' => 'sometimes|required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',

            'email' => ['sometimes', 'email:strict', new UniqueModel(User::class, 'email', $id), 'min:3', 'max:200'],
            'phone' => ['sometimes', new Phone(), new UniqueModel(User::class, 'phone', $id)],

            'password' => ['nullable', 'string', Password::min(8), 'max:255'],
            'password_current' => ['sometimes', 'current_password'],

            'fields' => ['array'],
        ];

        if ($this->has('password_confirmation')) {
            $res['password'][] = 'confirmed';
        }

        // Валідація способів доставки
        $res = $this->rulesShipping($res);

        return $res;
    }

    public function prepareForValidation()
    {
        $this->prepareForValidationPhoneValues('phone');
    }
}
