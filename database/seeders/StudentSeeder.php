<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        
        if ($schools->isEmpty()) {
            return;
        }

        $studentsData = [
            // School 1 - SMAN 1 Jakarta
            [
                'name' => 'Andi Pratama',
                'email' => 'student1@edufund.test',
                'school_id' => 1,
                'nisn' => '1234567890',
                'nim' => '2021001',
                'major' => 'Teknik Informatika',
                'semester' => 6,
                'gpa' => 3.85,
                'date_of_birth' => '2000-01-01',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 1, Jakarta',
                'academic_rank' => 'Pertama',
                'parent_name' => 'Bapak Sutrisno',
                'parent_income' => 3500000,
                'student_status' => 'Aktif',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'student2@edufund.test',
                'school_id' => 1,
                'nisn' => '0987654321',
                'nim' => '2021002',
                'major' => 'Ilmu Komputer',
                'semester' => 4,
                'gpa' => 3.70,
                'date_of_birth' => '2001-05-05',
                'phone' => '089876543210',
                'address' => 'Jl. Kebon Jeruk No. 2, Jakarta',
                'academic_rank' => 'Kedua',
                'parent_name' => 'Ibu Siti Aminah',
                'parent_income' => 4200000,
                'student_status' => 'Aktif',
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'student3@edufund.test',
                'school_id' => 1,
                'nisn' => '1122334455',
                'nim' => '2021003',
                'major' => 'Manajemen',
                'semester' => 2,
                'gpa' => 3.90,
                'date_of_birth' => '2002-08-15',
                'phone' => '087765432198',
                'address' => 'Jl. Sudirman No. 10, Jakarta',
                'academic_rank' => 'Pertama',
                'parent_name' => 'Bapak H. Rahman',
                'parent_income' => 2800000,
                'student_status' => 'Aktif',
            ],
            // School 2 - SMAN 2 Bandung
            [
                'name' => 'Dedi Kurniawan',
                'email' => 'student4@edufund.test',
                'school_id' => 2,
                'nisn' => '2233445566',
                'nim' => '2022001',
                'major' => 'Akuntansi',
                'semester' => 5,
                'gpa' => 3.65,
                'date_of_birth' => '2000-12-10',
                'phone' => '081398765432',
                'address' => 'Jl. Dago No. 25, Bandung',
                'academic_rank' => 'Ketiga',
                'parent_name' => 'Ibu Kartini',
                'parent_income' => 3900000,
                'student_status' => 'Aktif',
            ],
            [
                'name' => 'Eka Putri',
                'email' => 'student5@edufund.test',
                'school_id' => 2,
                'nisn' => '3344556677',
                'nim' => '2022002',
                'major' => 'Ilmu Komunikasi',
                'semester' => 3,
                'gpa' => 3.75,
                'date_of_birth' => '2001-03-20',
                'phone' => '082134567890',
                'address' => 'Jl. Cihampelas No. 15, Bandung',
                'academic_rank' => 'Kedua',
                'parent_name' => 'Bapak Agus Salim',
                'parent_income' => 5100000,
                'student_status' => 'Aktif',
            ],
            // School 3 - SMAN 3 Surabaya
            [
                'name' => 'Fajar Nugroho',
                'email' => 'student6@edufund.test',
                'school_id' => 3,
                'nisn' => '4455667788',
                'nim' => '2023001',
                'major' => 'Teknik Elektro',
                'semester' => 7,
                'gpa' => 3.80,
                'date_of_birth' => '1999-11-05',
                'phone' => '083812345678',
                'address' => 'Jl. Tunjungan No. 40, Surabaya',
                'academic_rank' => 'Kedua',
                'parent_name' => 'Ibu Dewi Sartika',
                'parent_income' => 4500000,
                'student_status' => 'Aktif',
            ],
            [
                'name' => 'Gita Permata',
                'email' => 'student7@edufund.test',
                'school_id' => 3,
                'nisn' => '5566778899',
                'nim' => '2023002',
                'major' => 'Psikologi',
                'semester' => 4,
                'gpa' => 3.95,
                'date_of_birth' => '2001-07-18',
                'phone' => '085698765432',
                'address' => 'Jl. Diponegoro No. 22, Surabaya',
                'academic_rank' => 'Pertama',
                'parent_name' => 'Bapak Dr. H. Sutopo',
                'parent_income' => 6200000,
                'student_status' => 'Aktif',
            ],
            // School 5 - SMAN 5 Medan
            [
                'name' => 'Hendra Wijaya',
                'email' => 'student8@edufund.test',
                'school_id' => 5,
                'nisn' => '6677889900',
                'nim' => '2024001',
                'major' => 'Sistem Informasi',
                'semester' => 6,
                'gpa' => 3.72,
                'date_of_birth' => '2000-04-25',
                'phone' => '081276543210',
                'address' => 'Jl. Gatot Subroso No. 60, Medan',
                'academic_rank' => 'Ketiga',
                'parent_name' => 'Ibu Rohani',
                'parent_income' => 3800000,
                'student_status' => 'Aktif',
            ],
            [
                'name' => 'Indah Sari',
                'email' => 'student9@edufund.test',
                'school_id' => 5,
                'nisn' => '7788990011',
                'nim' => '2024002',
                'major' => 'Bisnis Digital',
                'semester' => 2,
                'gpa' => 3.88,
                'date_of_birth' => '2002-09-30',
                'phone' => '082398765432',
                'address' => 'Jl. Imam Bonjol No. 18, Medan',
                'academic_rank' => 'Pertama',
                'parent_name' => 'Bapak H. Abdullah',
                'parent_income' => 5500000,
                'student_status' => 'Aktif',
            ],
        ];

        foreach ($studentsData as $studentData) {
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole(UserRole::STUDENT->value);

            StudentProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'school_id' => $studentData['school_id'],
                    'nisn' => $studentData['nisn'],
                    'nim' => $studentData['nim'],
                    'major' => $studentData['major'],
                    'semester' => $studentData['semester'],
                    'gpa' => $studentData['gpa'],
                    'date_of_birth' => $studentData['date_of_birth'],
                    'phone' => $studentData['phone'],
                    'address' => $studentData['address'],
                    'academic_rank' => $studentData['academic_rank'],
                    'parent_name' => $studentData['parent_name'],
                    'parent_income' => $studentData['parent_income'],
                    'student_status' => $studentData['student_status'],
                ]
            );
        }
    }
}
