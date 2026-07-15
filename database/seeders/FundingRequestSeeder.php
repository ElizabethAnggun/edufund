<?php

namespace Database\Seeders;

use App\Enums\EducationLevel;
use App\Enums\FundingCategory;
use App\Enums\FundingRequestStatus;
use App\Models\FundingRequest;
use App\Models\School;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FundingRequestSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();
        $students = StudentProfile::all();

        if ($students->isEmpty() || $schools->isEmpty()) {
            return;
        }

        $titles = [
            'Bantuan Biaya Kuliah Semester',
            'Bantuan Pembelian Buku dan Alat Tulis',
            'Bantuan Biaya Hunian Mahasiswa',
            'Bantuan Biaya Penelitian Tugas Akhir',
            'Bantuan Biaya Seminar dan Konferensi',
            'Bantuan Biaya Praktikum Lapangan',
            'Bantuan Biaya Sertifikasi Profesi',
            'Bantuan Biaya Magang',
        ];

        $descriptions = [
            'Bantuan biaya pendidikan untuk semester ini yang mencakup biaya kuliah, ujian, dan administrasi akademik. Mahasiswa berasal dari keluarga kurang mampu dan membutuhkan dukungan untuk melanjutkan pendidikan.',
            'Bantuan pembelian buku teks, referensi, dan alat tulis yang diperlukan untuk perkuliahan semester ini. Buku-buku ini sangat penting untuk mendukung pembelajaran dan tugas-tugas akademik.',
            'Bantuan biaya hunian/ kost selama semester ini. Mahasiswa tinggal jauh dari keluarga dan membutuhkan dukungan biaya hunian untuk fokus pada pembelajaran.',
            'Bantuan biaya penelitian untuk tugas akhir/skripsi/tesis. Meliputi biaya survei, wawancara, transportasi, dan analisis data yang diperlukan untuk menyelesaikan penelitian.',
            'Bantuan biaya mengikuti seminar dan konferensi nasional/internasional untuk memperluas wawasan dan jaringan akademik. Kegiatan ini penting untuk pengembangan karir mahasiswa.',
            'Bantuan biaya praktikum lapangan yang wajib diikuti sebagai syarat kelulusan. Meliputi biaya transportasi, akomodasi, dan peralatan praktikum.',
            'Bantuan biaya mengikuti program sertifikasi profesi yang diakui industri untuk meningkatkan daya saing lulusan di dunia kerja.',
            'Bantuan biaya program magang (internship) di perusahaan/instansi terkemuka untuk mendapatkan pengalaman kerja sebelum lulus.',
        ];

        $purposes = [
            'Biaya kuliah dan administrasi semester',
            'Pembelian buku dan alat tulis',
            'Biaya hunian/kost bulanan',
            'Biaya penelitian dan survei lapangan',
            'Biaya seminar dan konferensi',
            'Biaya praktikum lapangan',
            'Biaya sertifikasi profesi',
            'Biaya program magang',
        ];

        $rejectionReasons = [
            'Dana yang diminta melebihi batas yang disediakan untuk kategori ini. Mohon sesuaikan jumlah dana dengan kebutuhan yang sebenarnya.',
            'Dokumen pendukung belum lengkap. Silakan melengkapi surat keterangan tidak mampu dari keluarga dan transkrip nilai terbaru.',
            'Permintaan ini sudah pernah diajukan pada semester sebelumnya. Mohon cek riwayat pengajuan dana Anda.',
            'Kriteria kelayakan belum terpenuhi sesuai dengan kebijakan bantuan dana kampus.',
            'Informasi yang diberikan kurang detail. Mohon tambahkan rincian biaya yang lebih spesifik dan bukti pendukung yang jelas.',
        ];

        $statuses = [
            FundingRequestStatus::PENDING_SCHOOL_APPROVAL,
            FundingRequestStatus::APPROVED,
            FundingRequestStatus::REJECTED,
            FundingRequestStatus::ACTIVE,
            FundingRequestStatus::COMPLETED,
        ];

        $categories = [
            FundingCategory::TUITION,
            FundingCategory::BOOKS,
            FundingCategory::LIVING_EXPENSES,
            FundingCategory::RESEARCH,
            FundingCategory::OTHER,
        ];

        $educationLevels = [
            EducationLevel::S1,
            EducationLevel::S2,
            EducationLevel::S3,
            EducationLevel::D3,
            EducationLevel::SMA,
        ];

        $majors = ['Teknik Informatika', 'Manajemen', 'Akuntansi', 'Ilmu Komunikasi', 'Psikologi', 'Teknik Elektro', 'Sistem Informasi', 'Bisnis Digital'];

        // Create multiple funding requests per student
        foreach ($students as $studentIndex => $student) {
            $numRequests = rand(2, 4); // 2-4 requests per student

            for ($i = 0; $i < $numRequests; $i++) {
                $titleIndex = ($studentIndex + $i) % count($titles);
                $semester = $student->semester ?? rand(1, 8);
                $major = $student->major ?? $majors[array_rand($majors)];
                
                $status = $statuses[array_rand($statuses)];
                $category = $categories[array_rand($categories)];
                $educationLevel = $educationLevels[array_rand($educationLevels)];
                
                $amount = rand(1000000, 25000000); // 1M to 25M
                $deadlineMonths = rand(1, 12);
                
                $submittedAt = now()->subDays(rand(1, 60));
                $schoolApprovedAt = null;
                $rejectedAt = null;
                $rejectionReason = null;

                if ($status === FundingRequestStatus::APPROVED || $status === FundingRequestStatus::ACTIVE || $status === FundingRequestStatus::COMPLETED) {
                    $schoolApprovedAt = $submittedAt->copy()->addDays(rand(1, 7));
                } elseif ($status === FundingRequestStatus::REJECTED) {
                    $rejectedAt = $submittedAt->copy()->addDays(rand(1, 7));
                    $rejectionReason = $rejectionReasons[array_rand($rejectionReasons)];
                }

                FundingRequest::firstOrCreate(
                    [
                        'student_profile_id' => $student->id,
                        'title' => $titles[$titleIndex] . ' - ' . $major,
                    ],
                    [
                        'school_id' => $student->school_id ?? $schools->first()->id,
                        'description' => $descriptions[$titleIndex] . ' Mahasiswa sedang menempuh pendidikan di jurusan ' . $major . ' semester ' . $semester . '.',
                        'purpose' => $purposes[$titleIndex],
                        'total_amount' => $amount,
                        'currency' => 'IDR',
                        'education_level' => $educationLevel,
                        'category' => $category,
                        'status' => $status,
                        'submitted_at' => $submittedAt,
                        'school_approved_at' => $schoolApprovedAt,
                        'rejected_at' => $rejectedAt,
                        'rejection_reason' => $rejectionReason,
                        'deadline' => now()->addMonths($deadlineMonths),
                    ]
                );
            }
        }
    }
}