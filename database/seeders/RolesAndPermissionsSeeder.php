<?php

namespace Database\Seeders;

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
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $studentRole = Role::create(['name' => 'student']);

        // $permissions = [];

        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission]);
        // }

        // $adminRole->givePermissionTo([]);
        // $managerRole->givePermissionTo([]);
        // $studentRole->givePermissionTo([]);


        $routeCollection = Route::getRoutes()->get();
        // dd($routeCollection);
        foreach ($routeCollection as $value) {
            $name = $value->action;
            if (!empty($name['as'])) {
                $permission = $name['as'];
                $str = trim(strtolower($permission));
                $newStr = preg_replace('/[\s.,-]+/', ' ', $str);
                $permissions[] = $newStr;
                Permission::create([
                    'name' => $newStr
                ]);
            }
        }

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $adminUser->assignRole('admin');
    }
}
