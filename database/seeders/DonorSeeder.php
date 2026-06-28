<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Donor 1
        $user1 = User::firstOrCreate(
            ['email' => 'donor1@edufund.test'],
            [
                'name' => 'Charlie Wijaya',
                'password' => Hash::make('password'),
            ]
        );
        $user1->assignRole(UserRole::DONOR->value);

        // Donor 2
        $user2 = User::firstOrCreate(
            ['email' => 'donor2@edufund.test'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('password'),
            ]
        );
        $user2->assignRole(UserRole::DONOR->value);
    }
}
