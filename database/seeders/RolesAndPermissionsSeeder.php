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
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        $permissions = [
            'user can create courses',
            'user can edit courses',
            'user can delete courses',
            'user can view courses',
            'user can manage users',
            'user can manage course content',
            'user can purchase courses',
            'user can access purchased courses',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        if ($adminRole)
            $adminRole->givePermissionTo([
                'user can create courses',
                'user can edit courses',
                'user can delete courses',
                'user can view courses',
                'user can manage users',
                'user can manage course content',
            ]);

        if ($studentRole)
            $studentRole->givePermissionTo([
                'user can view courses',
                'user can purchase courses',
                'user can access purchased courses',
            ]);

        $routeCollection = Route::getRoutes()->get();
        $routeCollection = array_filter($routeCollection, function ($route) {
            return !(
                strpos($route->uri, '_ignition') === 0 || $route->uri === 'sanctum/csrf-cookie'
            );
        });
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

        $adminUser = User::firstOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        if ($adminUser)
            $adminUser->assignRole('admin');
    }
}
