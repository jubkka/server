<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'birthday' => '2000-01-01',
            'password' => 'Password123!',
        ]);

        $user = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'birthday' => '2000-01-01',
            'password' => 'Password123!',
        ]);

        $guest = User::create([
            'name' => 'guest',
            'email' => 'guest@gmail.com',
            'birthday' => '2000-01-01',
            'password' => 'Password123!',
        ]);

        $adminRole = Role::where('cipher', 'admin')->first();
        $userRole = Role::where('cipher', 'user')->first();
        $guestRole = Role::where('cipher', 'guest')->first();

        $admin->roles()->sync($adminRole->id);
        $user->roles()->sync($userRole->id);
        $guest->roles()->sync($guestRole->id);
    }
}
