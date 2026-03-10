<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Spatie permission config custom modules read
        $permissionConfig = collect(config('permission'))
            ->only(['page', 'post', 'user', 'product'])
            ->toArray();

        if (empty($permissionConfig)) {
            throw new \Exception('Permission modules not found in config/permission.php');
        }

        //Create permissions dynamically
        $allPermissions = [];
        foreach ($permissionConfig as $module => $actions) {
            foreach ($actions as $action) {
                $permission = "{$action} {$module}";

                Permission::firstOrCreate(['name' => $permission]);
                $allPermissions[] = $permission;
            }
        }

        //Roles create
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user  = Role::firstOrCreate(['name' => 'user']);

        //Admin → all permissions
        $admin->syncPermissions($allPermissions);

        //User → limited permissions (example)
        $userPermissions = array_filter(
            $allPermissions,
            fn ($p) => str_contains($p, 'view') || str_contains($p, 'edit')
        );

        $user->syncPermissions($userPermissions);
    }
}
