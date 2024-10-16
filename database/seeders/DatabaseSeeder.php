<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $role = Role::where('name', 'User')->first();

        $permissions = Permission::pluck('id')->toArray();

        $role->givePermissionTo($permissions);
        $user  = User::find(1);
        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin', 
                'email' => 'superadmin@horsefit.com',
                'password' => bcrypt('password'),
            ]);
        }
       $user->assignRole((int) $role->id);

    //uncomment if want to genearte users with factory
    //    User::factory(10)->create()->each(function ($user) use ($role) {
    //         $user->assignRole((int) $role->id);
    //     });
    }
}
