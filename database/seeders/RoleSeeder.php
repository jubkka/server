<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Создаем роли с уникальными наименованиями и шифрами
        Role::create([
            'name' => 'Admin',
            'cipher' => 'admin',
            'description' => 'Administrator with full access to all resources',
        ]);

        Role::create([
            'name' => 'User',
            'cipher' => 'user',
            'description' => 'Regular user with limited access',
        ]);

        Role::create([
            'name' => 'Guest',
            'cipher' => 'guest',
            'description' => 'Guest user with minimal access',
        ]);
    }
}
