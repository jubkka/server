<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();

        $adminRole = Role::where('cipher', 'admin')->first();
        $userRole = Role::where('cipher', 'user')->first();
        $guestRole = Role::where('cipher', 'guest')->first();

        $adminRole->permissions()->sync($permissions->pluck('id'));

        $userPermissions = Permission::whereIn('cipher', [
            'get-list-user',
            'read-user',
            'update-user',
        ])->get();

        $userRole->permissions()->sync($userPermissions->pluck('id'));

        $guestPermissions = Permission::where('cipher', 'get-list-user')->first();
        $guestRole->permissions()->sync([$guestPermissions->id]);
    }
}
