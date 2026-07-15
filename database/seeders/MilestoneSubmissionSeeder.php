<?php

namespace Database\Seeders;

use App\Enums\MilestoneSubmissionStatus;
use App\Models\Milestone;
use App\Models\MilestoneSubmission;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;

class MilestoneSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $students = StudentProfile::all();
        $milestones = Milestone::whereIn('status', [
            \App\Enums\MilestoneStatus::IN_PROGRESS,
            \App\Enums\MilestoneStatus::COMPLETED,
        ])->get();

        if ($students->isEmpty() || $milestones->isEmpty()) {
            return;
        }

        $descriptions = [
            'Saya telah menyelesaikan pembayaran uang kuliah semester ini. Berikut adalah bukti pembayaran dan transkrip nilai semester ini.',
            'Praktikum lapangan telah selesai dilaksanakan. Laporan praktikum dan dokumentasi kegiatan terlampir.',
            'Tugas akhir telah selesai ditulis dan diserahkan ke dosen pembimbing. Berikut adalah bukti serah terima dan persetujuan dosen.',
            'Seminar nasional telah diikuti dengan baik. Sertifikat kehadiran dan foto kegiatan terlampir.',
            'Buku dan alat tulis untuk semester ini telah dibeli. Struk pembelian dan daftar buku terlampir.',
        ];

        foreach ($milestones as $milestone) {
            // 70% chance to have a submission
            if (rand(1, 100) <= 70) {
                $student = $milestone->fundingRequest->studentProfile;
                
                if (!$student) {
                    continue;
                }

                $statuses = [
                    MilestoneSubmissionStatus::VERIFIED,
                    MilestoneSubmissionStatus::VERIFIED,
                    MilestoneSubmissionStatus::PENDING,
                    MilestoneSubmissionStatus::REJECTED,
                ];
                $status = $statuses[array_rand($statuses)];

                $submittedAt = $milestone->due_date->subDays(rand(7, 30));

                $submission = MilestoneSubmission::create([
                    'milestone_id' => $milestone->id,
                    'student_profile_id' => $student->id,
                    'description' => $descriptions[array_rand($descriptions)],
                    'status' => $status,
                    'submitted_at' => $submittedAt,
                ]);

                // Create verification for verified submissions
                if ($status === MilestoneSubmissionStatus::VERIFIED) {
                    \App\Models\Verification::create([
                        'verifiable_id' => $submission->id,
                        'verifiable_type' => MilestoneSubmission::class,
                        'verifier_id' => 1, // Admin user
                        'status' => \App\Enums\VerificationStatus::APPROVED,
                        'feedback' => 'Pengajuan milestone disetujui. Semua dokumen lengkap dan valid.',
                        'verified_at' => $submittedAt->copy()->addDays(rand(1, 7)),
                    ]);
                }
            }
        }
    }
}