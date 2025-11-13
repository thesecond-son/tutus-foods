<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Create the admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@tutusfoods.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('tutusfoods098'), // You can change this
            ]
        );

        // 2️⃣ Create or fetch the admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 3️⃣ Optionally, give full permissions
        $permissions = Permission::pluck('name')->toArray();
        $adminRole->syncPermissions($permissions);

        // 4️⃣ Assign the role to the user
        $admin->assignRole($adminRole);
    }
}
