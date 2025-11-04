<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Dev',
                'email' => 'dev@app.com',
                'phone' => '380960000000',
                'password' => 'password',
                'email_verified_at' => now(),
                'status' => User::STATUS_ACTIVE,
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Optimus',
                'email' => 'admin@app.com',
                'phone' => '380961111111',
                'password' => 'password',
                'email_verified_at' => now(),
                'status' => User::STATUS_ACTIVE,
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Client',
                'email' => 'client@app.com',
                'phone' => '380962222222',
                'password' => 'password',
                'email_verified_at' => now(),
                'status' => User::STATUS_ACTIVE,
                'role' => User::ROLE_CLIENT,
            ],
            [
                'name' => 'Whole',
                'email' => 'whole@app.com',
                'phone' => '380963333333',
                'password' => 'password',
                'email_verified_at' => now(),
                'status' => User::STATUS_ACTIVE,
                'role' => User::ROLE_CLIENT,
            ],
            [
                'name' => 'Dropshipper',
                'email' => 'drop@app.com',
                'phone' => '380964444444',
                'password' => 'password',
                'email_verified_at' => now(),
                'status' => User::STATUS_ACTIVE,
                'role' => User::ROLE_CLIENT,
            ],
        ];

        $table = [];
        foreach ($users as $data) {
            /** @var User $user */
            $user = User::firstOrCreate(Arr::only($data, 'email'), $data);

            $table[] = [$user->id, $user->email, 'password', url('/admin/login')];
        }

        $this->command->getOutput()->newLine();
        $this->command->alert('Users');
        $this->command->table(['#', 'Email', 'Password', 'Url'], $table);
        $this->command->getOutput()->newLine(2);
    }
}
