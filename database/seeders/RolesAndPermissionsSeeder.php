<?php

namespace Database\Seeders;

use App\GlobalVariables\PermissionVariable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        $permissions = array_filter(array_column(PermissionVariable::allRoutes(), 'permission'));
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        if ($adminRole) {
            $role = 'admin';
            $adminRole->givePermissionTo(
                array_column(
                    array_filter(
                        PermissionVariable::allRoutes(),
                        function ($item) use ($role) {
                            return in_array($role, $item['roles']);
                        }
                    ),
                    'permission'
                )
            );
        }

        if ($studentRole)
            $role = 'student';
        $studentRole->givePermissionTo(
            array_column(
                array_filter(
                    PermissionVariable::allRoutes(),
                    function ($item) use ($role) {
                        return in_array($role, $item['roles']);
                    }
                ),
                'permission'
            )
        );

        $adminUser = User::firstOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        if ($adminUser)
            $adminUser->assignRole('admin');
    }
}
