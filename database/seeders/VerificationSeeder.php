<?php

namespace Database\Seeders;

use App\Enums\EducationLevel;
use App\Enums\VerificationStatus;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user as verifier
        $admin = User::where('email', 'admin@edufund.test')->first();
        
        if (!$admin) {
            return;
        }

        // Get schools and students
        $schools = School::all();
        $students = StudentProfile::all();

        // Create verifications for schools
        foreach ($schools as $school) {
            // School verification
            $statuses = [
                VerificationStatus::APPROVED,
                VerificationStatus::PENDING,
                VerificationStatus::REJECTED,
            ];

            $status = $statuses[array_rand($statuses)];
            $verifiedAt = null;

            if ($status === VerificationStatus::APPROVED) {
                $verifiedAt = now()->subDays(rand(1, 30));
            }

            Verification::firstOrCreate(
                [
                    'verifiable_id' => $school->id,
                    'verifiable_type' => School::class,
                ],
                [
                    'verifier_id' => $admin->id,
                    'status' => $status,
                    'feedback' => $this->getSchoolFeedback($status),
                    'verified_at' => $verifiedAt,
                ]
            );
        }

        // Create verifications for students
        foreach ($students as $student) {
            // Each student gets 1-2 verifications
            $numVerifications = rand(1, 2);
            
            for ($i = 0; $i < $numVerifications; $i++) {
                $statuses = [
                    VerificationStatus::APPROVED,
                    VerificationStatus::PENDING,
                    VerificationStatus::REJECTED,
                ];

                $status = $statuses[array_rand($statuses)];
                $verifiedAt = null;

                if ($status === VerificationStatus::APPROVED) {
                    $verifiedAt = now()->subDays(rand(1, 30));
                }

                Verification::create([
                    'verifiable_id' => $student->id,
                    'verifiable_type' => StudentProfile::class,
                    'verifier_id' => $admin->id,
                    'status' => $status,
                    'feedback' => $this->getStudentFeedback($status),
                    'verified_at' => $verifiedAt,
                ]);
            }
        }
    }

    private function getSchoolFeedback(VerificationStatus $status): string
    {
        return match($status) {
            VerificationStatus::APPROVED => 'Verifikasi berhasil. Semua dokumen dan data sekolah telah diverifikasi dan sesuai dengan ketentuan.',
            VerificationStatus::PENDING => 'Verifikasi sedang dalam proses. Tim kami sedang melakukan pengecekan dokumen dan data sekolah.',
            VerificationStatus::REJECTED => 'Verifikasi ditolak. Dokumen akreditasi tidak jelas atau tidak sesuai dengan data yang tercatat. Mohon unggah ulang dokumen yang valid.',
        };
    }

    private function getStudentFeedback(VerificationStatus $status): string
    {
        $feedbacks = [
            'approved' => [
                'Verifikasi berhasil. Data akademik dan dokumen pendukung telah diverifikasi dan valid.',
                'Profil siswa telah diverifikasi. Semua informasi akurat dan sesuai dengan dokumen resmi.',
                'Verifikasi approved. Transkrip nilai dan identitas siswa telah dikonfirmasi.',
            ],
            'pending' => [
                'Verifikasi sedang dalam proses. Tim sedang mengecek transkrip nilai dan dokumen identitas.',
                'Menunggu verifikasi dokumen pendukung dari sekolah.',
            ],
            'rejected' => [
                'Verifikasi ditolak. Transkrip nilai tidak jelas atau tidak sesuai dengan data yang dilaporkan. Mohon upload ulang.',
                'Dokumen identitas tidak valid. Mohon upload foto KTP/SIM yang jelas dan masih berlaku.',
                'Data NISN/NIM tidak sesuai dengan dokumen. Mohon perbaiki informasi profil Anda.',
            ],
        ];

        $statusValue = $status->value;
        $statusFeedbacks = $feedbacks[$statusValue];
        return $statusFeedbacks[array_rand($statusFeedbacks)];
    }
}