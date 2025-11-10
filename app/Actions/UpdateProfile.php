<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

final class UpdateProfile
{
    use AsAction;

    public function handle(User $user, array $data)
    {
        if ($password = Arr::get($data, 'password')) {
            $data['password'] = $password;
        } else {
            unset($data['password']);
        }
        $data['added'] = array_merge($user->added ?: [], Arr::only($data, ['shipping', 'payment']));

        $user->update(Arr::only($data, [
            'name', 'lastname', 'middlename', 'birthday', 'email', 'phone', 'gender', 'channels', 'status', 'password', 'added'
        ]));

        //$user->mediaManageRefresh($data, Auth::user());

        return $user;
    }
}
