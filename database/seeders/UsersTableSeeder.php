<?php

namespace Database\Seeders;

// database/seeders/UsersTableSeeder.php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign the "admin" role to the admin user
        $adminRole = Role::where('name', 'admin')->first();
        $admin->assignRole($adminRole);

        // Create a regular user
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign the "user" role to the regular user
        $userRole = Role::where('name', 'user')->first();
        $user->assignRole($userRole);
    }
}
