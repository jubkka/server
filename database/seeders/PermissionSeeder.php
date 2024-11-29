<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = ['user', 'role', 'permission'];

        foreach ($entities as $entity) {
            Permission::create([
                'name' => "Get list of {$entity}s",
                'cipher' => "get-list-{$entity}",
                'description' => "Permission to get list of {$entity}s",
            ]);

            Permission::create([
                'name' => "Read {$entity}",
                'cipher' => "read-{$entity}",
                'description' => "Permission to read a specific {$entity}",
            ]);

            Permission::create([
                'name' => "Create {$entity}",
                'cipher' => "create-{$entity}",
                'description' => "Permission to create a new {$entity}",
            ]);

            Permission::create([
                'name' => "Update {$entity}",
                'cipher' => "update-{$entity}",
                'description' => "Permission to update an existing {$entity}",
            ]);

            Permission::create([
                'name' => "Delete {$entity}",
                'cipher' => "delete-{$entity}",
                'description' => "Permission to delete an existing {$entity}",
            ]);

            Permission::create([
                'name' => "Restore {$entity}",
                'cipher' => "restore-{$entity}",
                'description' => "Permission to restore a deleted {$entity}",
            ]);
        }
    }
}
