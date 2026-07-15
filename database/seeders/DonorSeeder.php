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
        $donorsData = [
            [
                'name' => 'Charlie Wijaya',
                'email' => 'donor1@edufund.test',
                'wallet_address' => 'GCHARIEEWALLETADDRESS000000000000000000000000000000000000000000',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'donor2@edufund.test',
                'wallet_address' => 'GDEWILESTARIWALLETADDRESS00000000000000000000000000000000000000',
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'donor3@edufund.test',
                'wallet_address' => 'GEKOPRASETYOWALLETADDRESS00000000000000000000000000000000000000',
            ],
            [
                'name' => 'Fitri Handayani',
                'email' => 'donor4@edufund.test',
                'wallet_address' => 'GFITRIHANDAYANIWALLETADDRESS0000000000000000000000000000000000000',
            ],
            [
                'name' => 'Gunawan Setiawan',
                'email' => 'donor5@edufund.test',
                'wallet_address' => 'GGUNAWANSETIAWANWALLETADDRESS000000000000000000000000000000000000',
            ],
            [
                'name' => 'Hana Permata',
                'email' => 'donor6@edufund.test',
                'wallet_address' => 'GHANAPERMATAWALLETADDRESS000000000000000000000000000000000000000',
            ],
            [
                'name' => 'Indra Kusuma',
                'email' => 'donor7@edufund.test',
                'wallet_address' => 'GINDRAKUSUMAWALLETADDRESS000000000000000000000000000000000000000',
            ],
            [
                'name' => 'Joko Widodo',
                'email' => 'donor8@edufund.test',
                'wallet_address' => 'GJOKOWIDODOWALLETADDRESS0000000000000000000000000000000000000000',
            ],
        ];

        foreach ($donorsData as $donorData) {
            $user = User::firstOrCreate(
                ['email' => $donorData['email']],
                [
                    'name' => $donorData['name'],
                    'password' => Hash::make('password'),
                    'wallet_address' => $donorData['wallet_address'],
                ]
            );
            $user->assignRole(UserRole::DONOR->value);
        }
    }
}
