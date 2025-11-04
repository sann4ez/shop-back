<?php


namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

final class UpdateUserAction
{
    use AsAction;

    public function handle(User $user, array $data)
    {
        if ($password = Arr::get($data, 'password')) {
            $data['password'] = $password;
        } else {
            unset($data['password']);
        }

        $user->update(Arr::only($data, [
            'login', 'email', 'phone', 'name', 'lastname', 'middlename', 'birthday', 'gender', 'added', 'comment', 'status', 'password',
        ]));

        if (Arr::has($data, 'roles')) {
            $user->syncRoles(Arr::get($data, 'roles'));
        }

        return $user;
    }
}
