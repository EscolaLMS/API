<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $adminPermissions = [
            'access dashboard',
            'user manage',
        ];
        $instructorPermissions = [
            'access dashboard',
        ];
        foreach ($instructorPermissions + $adminPermissions as $permission) {
            $this->createPermissionWithName($permission);
        }

        Role::create(['name' => UserRole::STUDENT]);
        $instructor = Role::create(['name' => UserRole::INSTRUCTOR]);
        $instructor->givePermissionTo($instructorPermissions);
        $admin = Role::create(['name' => UserRole::ADMIN]);
        $admin->givePermissionTo($adminPermissions);
    }

    public function createPermissionWithName(string $name)
    {
        Permission::create(['name' => $name]);
    }
}
