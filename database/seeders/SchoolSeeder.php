<?php

namespace Database\Seeders;

use App\Enums\SchoolVerificationStatus;
use App\Enums\UserRole;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolsData = [
            [
                'name' => 'SMAN 1 Jakarta',
                'email' => 'school1@edufund.test',
                'address' => 'Jl. Sudirman No. 1, Jakarta',
                'phone' => '021-1234567',
                'npsn' => '12345678',
                'headmaster_name' => 'Dr. Ahmad Wijaya, M.Pd',
                'accreditation_number' => 'AKR-001-2024',
                'stellar_wallet_address' => 'GAXSCHOOLONEEDUFUNDTESTNETWALLETADDRESS0000000000000000001',
                'verification_status' => SchoolVerificationStatus::VERIFIED,
            ],
            [
                'name' => 'SMAN 2 Bandung',
                'email' => 'school2@edufund.test',
                'address' => 'Jl. Asia Afrika No. 100, Bandung',
                'phone' => '022-7654321',
                'npsn' => '23456789',
                'headmaster_name' => 'Dra. Siti Nurhaliza, M.M',
                'accreditation_number' => 'AKR-002-2024',
                'stellar_wallet_address' => 'GAXSCHOOLTWOEDUFUNDTESTNETWALLETADDRESS0000000000000000002',
                'verification_status' => SchoolVerificationStatus::PENDING,
            ],
            [
                'name' => 'SMAN 3 Surabaya',
                'email' => 'school3@edufund.test',
                'address' => 'Jl. Pemuda No. 33, Surabaya',
                'phone' => '031-5551234',
                'npsn' => '34567890',
                'headmaster_name' => 'Prof. Bambang Sutrisno, Ph.D',
                'accreditation_number' => 'AKR-003-2024',
                'stellar_wallet_address' => 'GAXSCHOOLTHREEEDUFUNDTESTNETWALLETADDRESS0000000000000000003',
                'verification_status' => SchoolVerificationStatus::VERIFIED,
            ],
            [
                'name' => 'SMAN 4 Yogyakarta',
                'email' => 'school4@edufund.test',
                'address' => 'Jl. Malioboro No. 88, Yogyakarta',
                'phone' => '0274-888999',
                'npsn' => '45678901',
                'headmaster_name' => 'Dra. Endang Rahayu, M.Pd',
                'accreditation_number' => 'AKR-004-2024',
                'stellar_wallet_address' => 'GAXSCHOOLFOUREDUFUNDTESTNETWALLETADDRESS0000000000000000004',
                'verification_status' => SchoolVerificationStatus::REJECTED,
            ],
            [
                'name' => 'SMAN 5 Medan',
                'email' => 'school5@edufund.test',
                'address' => 'Jl. Gatot Subroto No. 55, Medan',
                'phone' => '061-777888',
                'npsn' => '56789012',
                'headmaster_name' => 'Dr. H. Muhammad Yusuf, M.M',
                'accreditation_number' => 'AKR-005-2024',
                'stellar_wallet_address' => 'GAXSCHOOLFIVEEDUFUNDTESTNETWALLETADDRESS0000000000000000005',
                'verification_status' => SchoolVerificationStatus::VERIFIED,
            ],
        ];

        foreach ($schoolsData as $index => $schoolData) {
            $user = User::firstOrCreate(
                ['email' => $schoolData['email']],
                [
                    'name' => $schoolData['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole(UserRole::SCHOOL->value);

            $verifiedAt = null;
            if ($schoolData['verification_status'] === SchoolVerificationStatus::VERIFIED) {
                $verifiedAt = now()->subDays(rand(5, 30));
            }

            School::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $schoolData['name'],
                    'npsn' => $schoolData['npsn'],
                    'address' => $schoolData['address'],
                    'headmaster_name' => $schoolData['headmaster_name'],
                    'phone' => $schoolData['phone'],
                    'email' => $schoolData['email'],
                    'accreditation_number' => $schoolData['accreditation_number'],
                    'stellar_wallet_address' => $schoolData['stellar_wallet_address'],
                    'verification_status' => $schoolData['verification_status']->value,
                    'verified_at' => $verifiedAt,
                ]
            );
        }
    }
}
