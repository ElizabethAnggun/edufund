<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view admin dashboard',
            'manage users',
            'verify schools',
            'view reports',
            'manage campaigns',
            'view all campaigns',
            'create campaigns',
            'edit campaigns',
            'delete campaigns',
            'verify milestones',
            'create funding requests',
            'edit funding requests',
            'submit milestones',
            'make donations',
            'view own donations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
