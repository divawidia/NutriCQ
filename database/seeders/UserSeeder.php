<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'user']);
        $role3 = Role::create(['name' => 'doctor']);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin12345'),
            'no_telp' => '08432432432',
            'status' => 'active'
        ]);
        $admin->assignRole($role1);

        $admin = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user12345'),
            'no_telp' => '08432432432',
            'status' => 'active'
        ]);
        $admin->assignRole($role2);

        $admin = User::create([
            'name' => 'doctor',
            'email' => 'doctor@gmail.com',
            'password' => Hash::make('doctor12345'),
            'no_telp' => '08432432432',
            'status' => 'active'
        ]);
        $admin->assignRole($role3);

    }
}
