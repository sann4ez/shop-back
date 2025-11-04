<?php

namespace App\Http\Admin\Requests;

use App\Models\User;
use App\Rules\Phone;
use App\Rules\UniqueModel;
use Illuminate\Validation\Rule;

final class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = optional($this->route('user'))->id;

        $rules = [
            'name' => 'nullable|string',
            'lastname' => 'nullable|string',
            'middlename' => 'nullable|string',
            'birthday' => 'nullable|date',
            'email' => ['nullable', 'email:strict', new UniqueModel(User::class, 'email', $id),],
            'phone' => ['nullable', 'string', new UniqueModel(User::class, 'phone', $id), new Phone()],
//            'channels' => ['nullable', 'array', Rule::in(User::notifyChannelsList('key'))],
            'status' => ['required', 'required', Rule::in(User::statusesList('key'))],
            'comment' => 'nullable|string',
            'notifies' => 'nullable|array',
            'fields' => 'nullable|array',
        ];

        if ($this->isMethod('post') || $this->input('password')) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return  $rules;
    }

    public function passedValidation()
    {
        if ($this->phone) {
            $this->merge(['phone' => trim($this->phone, '+')]);
        }
    }

    public function prepareForValidation()
    {
        $this->prepareForValidationPhoneValues(['phone'], true);
    }
}
