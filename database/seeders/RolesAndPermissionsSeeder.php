<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clearOldPermisssions();


        // Права
        $permissions = [
            ['name' => 'dashboard.auth', 'title' => 'Авторизація в адмінпанель',],
            ['name' => 'statistic.view', 'title' => 'Перегляд статистики',],

            ['name' => 'settings.system', 'title' => 'Налаштування системні',],
            ['name' => 'settings.content', 'title' => 'Налаштування контентні',],
            ///['name' => 'profile.delete', 'title' => 'Видаляти свій профиль'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(Arr::only($permission, ['name']), $permission);
        }


        /*------------------------------------------------------------
        #### Генерація інших назв прав для сутностей:
        1. Сутність: page, article,...
        2. Дія: create, view, update, delete
        3. Власність (опційно): own

        Приклад назв прав:
        'page.create', 'product.update', 'article.update', 'article.delete.own', 'user.delete',
         ------------------------------------------------------------*/
        $actions = [
            'read' => 'Перегляд',
            'create' => 'Створення',
            'update' => 'Редагування',
            'delete' => 'Видалення',
        ];

        $entities = [
            'role' => 'Ролі',
            'user' => 'Користувачі',
            'page' => 'Сторінки',

            'post' => 'Публікації',

            //'lead' => 'Ліди',
        ];

        $accessories = ['own' => 'власні'];
        $entitiesAccessories = ['project', 'task', 'payout', 'timelog', 'stage', 'comment',];

        // https://i.imgur.com/oYrrtz0.png
        $assignedsSuffixTitle = 'призначені';
        $assignedsEntities = ['task', 'project'];

        // Не генерувати наступних прав
        $doNotCreatePermissions = [/*'project.create', 'task.create', 'payout.create', 'timelog.create', 'stage.create', 'comment.create',*/];

        foreach ($entities as $entityName => $entityTitle) {
            foreach ($actions as $actionName => $actionTitle) {

                $permission = "$entityName.$actionName";
                if (!in_array($permission, $doNotCreatePermissions)) {
                    $permissionData = [
                        'name' => $permission,
                        'title' => "{$entityTitle} - {$actionTitle}",
                        //'guard_name' => 'web',
                    ];
                    Permission::updateOrCreate(Arr::only($permissionData, 'name'), $permissionData);
                    $allPermissions[] = $permission;


                    if (in_array($entityName, $entitiesAccessories) && ($actionName !== 'create')) {
                        foreach ($accessories as $accessorySuffix => $accessoryTitle) {
                            if (in_array($entityName, $assignedsEntities) && $actionName !== 'delete') {
                                $accessoryTitle = "{$accessoryTitle}/{$assignedsSuffixTitle}";
                            }
                            $permissionDataAccessory = [
                                'name' => "$permission.$accessorySuffix",
                                'title' => "{$entityTitle} - {$actionTitle} [{$accessoryTitle}]",
                            ];
                            $allPermissions[] = "$permission.$accessorySuffix";
                            Permission::updateOrCreate(Arr::only($permissionDataAccessory, 'name'), $permissionDataAccessory);
                        }
                    }
                }
            }
        }

        // Дооновлення прав
        $permissions = [
           // ['name' => 'project.users.manage', 'title' => 'Проєкти - Керувати учасниками',],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(Arr::only($permission, ['name']), $permission);
        }


        // Всі ролі
        $roles = [
            ['name' => 'admin', 'title' => 'Admin'],
            ['name' => 'manager', 'title' => 'Manager'],
            ['name' => 'client', 'title' => 'Client'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(Arr::only($role, ['name']), $role);
        }

        // Призначення адміну всіх прав
        //$roleAdmin = Role::whereName('admin')->first();
        //$roleAdmin->syncPermissions(Permission::all());


        // Призначення ролей для користувачів
        $userHasRole = [
            'dev@app.com' => 'admin',
            'admin@app.com' => 'admin',
            'manager@app.com' => 'manager',
        ];

        foreach ($userHasRole as $email => $role) {
            /** @var User $user */
            if ($user = \App\Models\User::whereEmail($email)->first()) {
                $user->assignRole($role);
                $this->command->info("Роль {$role} призначено для користувача {$user->name}");
            }
        }

        app()['cache']->forget('spatie.permission.cache');
    }

    protected function clearOldPermisssions()
    {
        $deletedPermissions = [
            'some.manage',
        ];
        foreach ($deletedPermissions as $permission) {
            Permission::whereName($permission)->delete();
        }
    }
}
