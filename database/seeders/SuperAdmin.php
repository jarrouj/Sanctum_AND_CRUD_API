<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'add content',
            'edit content',
            'view ontenct',
            'delete content',
            'manage admins',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superAdminRole = Role::create([
            'name' => 'super-admin'
        ]);

        $adminRole = Role::create([
            'name' => 'admin'
        ]);

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@gmail.com',
            'password' => bcrypt('password')
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        $superAdmin->assignRole($superAdminRole);
        $superAdmin->givePermissionTo($permissions);

        $adminPermissions = array_diff($permissions, ['manage users']);
        $admin->assignRole($adminRole);
        $admin->givePermissionTo($adminPermissions);
    }






}
