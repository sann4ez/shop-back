<?php

namespace App\Actions;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

final class StoreUserAction
{
    use AsAction;

    public function handle(array $data)
    {
        if ($password = Arr::get($data, 'password')) {
            $data['password'] = $password;
        } else {
            unset($data['password']);
        }

        $data['status'] = Arr::get($data, 'status', User::STATUS_ACTIVE);
        $data['locale'] = Arr::get($data, 'lang_code', 'uk');

        /** @var User $user */
        $user = User::create(Arr::only($data, [
            'name', 'lastname', 'middlename', 'login', 'email', 'phone', 'birthday', 'added', 'comment', 'status', 'password', 'locale',
        ]));

        if (Arr::has($data, 'roles')) {
            $user->assignRole(Arr::get($data, 'roles'));
        } else {
            $user->assignRole([Role::DEFAULT_ROLE_CLIENT]);
        }


        return $user;
    }
}
