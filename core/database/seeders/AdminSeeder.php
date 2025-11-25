<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Admin::where('email', 'admin@estuairevisa.com')->first();
        if (!$user)
        {
            $user = new Admin();
            $user->name = 'Estuaire Admin';
            $user->username = 'estuaire_admin';
            $user->email = 'admin@estuairevisa.com';
            $user->password = Hash::make('EstuaireVisa@2025!');
            $user->save();
        }

        $permissions = [];
        $allPermissions = Permission::all();
        foreach ($allPermissions as $permission){
            array_push($permissions, $permission->id);
        }

        $role = Role::where('name','admin')->first();
        if (!$role)
        {
            $role = new Role();
            $role->name = 'Admin';
            $role->guard_name = 'admin';
            $role->save();
        }

        foreach ($permissions as $item) {
            $role->givePermissionTo($item);
        }

        $user->assignRole($role);
    }
}
