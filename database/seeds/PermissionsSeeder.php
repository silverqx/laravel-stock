<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Permissions.
     *
     * @return array
     */
    private function permissions(): array
    {
        return [
            // Product
            'viewAny product',
            'view product',
            'create product',
            'edit own product',
            'edit any product',
            'delete own product',
            'delete any product',

            // User
            'manage users',

            // These permissions are not used for now
            'restore own product',
            'restore any product',
            'forceDelete own product',
            'forceDelete any product',
        ];
    }

    /**
     * Roles.
     *
     * @return array
     */
    private function roles(): array
    {
        return [
            'administrator' => $this->permissions(),

            'operator' => [
                'viewAny product',
                'view product',
                'create product',
                'edit own product',
                'delete own product',
            ],

            'client'   => [
                'viewAny product',
                'view product',
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create Permissions
        collect($this->permissions())->each(function ($permission) {
            Permission::create(['name' => $permission]);
        });

        // Create Roles
        collect($this->roles())->each(function ($permissions, $role) {
            $roleModel = Role::create(['name' => $role]);

            collect($permissions)->each(function ($permission) use ($roleModel) {
                $roleModel->givePermissionTo($permission);
            });
        });
    }
}
