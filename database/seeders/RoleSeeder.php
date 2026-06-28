<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin role
        $adminRole = Role::firstOrCreate(['name' => UserRole::ADMIN->value, 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // School role
        $schoolRole = Role::firstOrCreate(['name' => UserRole::SCHOOL->value, 'guard_name' => 'web']);
        $schoolRole->syncPermissions([
            'create campaigns',
            'edit campaigns',
            'delete campaigns',
            'verify milestones',
            'view all campaigns',
        ]);

        // Student role
        $studentRole = Role::firstOrCreate(['name' => UserRole::STUDENT->value, 'guard_name' => 'web']);
        $studentRole->syncPermissions([
            'create funding requests',
            'edit funding requests',
            'submit milestones',
        ]);

        // Donor role
        $donorRole = Role::firstOrCreate(['name' => UserRole::DONOR->value, 'guard_name' => 'web']);
        $donorRole->syncPermissions([
            'make donations',
            'view own donations',
            'view all campaigns',
        ]);
    }
}
