<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'content.view',
            'content.create',
            'content.edit',
            'content.delete',
            'users.manage',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole = Role::findOrCreate('admin', 'web');
        $userRole = Role::findOrCreate('user', 'web');

        $adminRole->syncPermissions($permissions);
        $userRole->syncPermissions([
            'content.view',
            'content.create',
            'content.edit',
        ]);

        $firstUser = User::query()->orderBy('id')->first();

        if ($firstUser) {
            $firstUser->syncRoles(['admin']);
        }

        User::query()
            ->where('id', '!=', optional($firstUser)->id)
            ->get()
            ->each(function (User $user): void {
                if (! $user->hasAnyRole(['admin', 'user'])) {
                    $user->assignRole('user');
                }
            });
    }
}
