<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Admin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        Permission::create(['name' => 'add content']);
        Permission::create(['name' => 'edit content']);
        Permission::create(['name' => 'view content']);
        Permission::create(['name' => 'delete content']);
        Permission::create(['name' => 'manage admins']);
        Permission::create(['name' => 'manage users']);


        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@gmail.com',
            'password' => bcrypt('password')
        ]);
        $superAdmin->assignRole($role);


       $admin =Role::create(['name' => 'admin']);
       $arrayOfPermissionNames = ['add content', 'edit content','view content','delete content'];

       $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
        return ['name' => $permission];
    });
       $admin->givePermissionTo($permissions);

    }



}
