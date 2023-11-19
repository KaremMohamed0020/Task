<?php

namespace Database\Seeders;

// database/seeders/RolesAndPermissionsSeeder.php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // Permissions
        Permission::create(['name' => 'Create transactions']);
        Permission::create(['name' => 'View transactions']);
        Permission::create(['name' => 'Record payments']);
        Permission::create(['name' => 'Generate reports']);
        Permission::create(['name' => 'View user transactions']);

        // Assign permissions to roles
        Role::findByName('admin')->givePermissionTo(['Create transactions', 'View transactions', 'Record payments', 'Generate reports']);
        Role::findByName('user')->givePermissionTo('View user transactions');
    }
}
