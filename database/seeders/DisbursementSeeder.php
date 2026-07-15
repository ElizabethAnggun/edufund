<?php

namespace Database\Seeders;

use App\Enums\DisbursementStatus;
use App\Models\Disbursement;
use App\Models\FundingRequest;
use App\Models\Milestone;
use App\Models\School;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DisbursementSeeder extends Seeder
{
    /**
     * Seed disbursement data for school role.
     * Creates disbursements for completed milestones across multiple schools.
     */
    public function run(): void
    {
        $schools = School::all();
        $students = StudentProfile::all();

        if ($schools->isEmpty() || $students->isEmpty()) {
            return;
        }

        // Get completed milestones that don't have disbursements yet
        $completedMilestones = Milestone::where('status', 'completed')
            ->whereDoesntHave('disbursement')
            ->get();

        if ($completedMilestones->isEmpty()) {
            return;
        }

        $notes = [
            'Pencairan dana untuk milestone pertama. Dana telah diterbitkan ke alamat stellar wallet siswa.',
            'Pencairan dana tahap kedua. Semua dokumen verifikasi telah lengkap dan disetujui.',
            'Pencairan dana final. Proyek telah selesai dan semua milestone telah diselesaikan.',
            'Pencairan dana untuk biaya operasional kegiatan. Transaksi berhasil diproses.',
            'Pencairan dana untuk pembelian peralatan dan bahan praktikum.',
        ];

        $statuses = [
            DisbursementStatus::COMPLETED,
            DisbursementStatus::PENDING,
            DisbursementStatus::FAILED,
        ];

        // Create disbursements for completed milestones
        foreach ($completedMilestones as $milestone) {
            $fundingRequest = $milestone->fundingRequest;
            
            if (!$fundingRequest) {
                continue;
            }

            $school = $fundingRequest->school;
            $student = $fundingRequest->studentProfile;

            if (!$school || !$student) {
                continue;
            }

            // Determine disbursement amount (could be partial or full)
            $amount = $milestone->amount ?? rand(1000000, 10000000);
            $status = $statuses[array_rand($statuses)];
            
            $releasedAt = null;
            $txHash = null;
            $fromAddress = null;
            $toAddress = null;

            if ($status === DisbursementStatus::COMPLETED) {
                $releasedAt = now()->subDays(rand(1, 30));
                $txHash = 'TX' . Str::random(64);
                $fromAddress = $school->stellar_wallet_address;
                $toAddress = 'G' . Str::random(55) . 'S'; // Simulated student stellar address
            } elseif ($status === DisbursementStatus::PENDING) {
                $txHash = 'TX' . Str::random(64);
                $fromAddress = $school->stellar_wallet_address;
                $toAddress = 'G' . Str::random(55) . 'S';
            }

            Disbursement::create([
                'funding_request_id' => $fundingRequest->id,
                'milestone_id' => $milestone->id,
                'school_id' => $school->id,
                'student_profile_id' => $student->id,
                'amount' => $amount,
                'currency' => 'IDR',
                'status' => $status,
                'tx_hash' => $txHash,
                'from_address' => $fromAddress,
                'to_address' => $toAddress,
                'released_at' => $releasedAt,
                'notes' => $notes[array_rand($notes)],
            ]);
        }

        // Create additional disbursements for active/approved funding requests
        $activeFundingRequests = FundingRequest::whereIn('status', [
            \App\Enums\FundingRequestStatus::APPROVED,
            \App\Enums\FundingRequestStatus::ACTIVE,
        ])
        ->whereDoesntHave('disbursements')
        ->get();

        foreach ($activeFundingRequests as $fundingRequest) {
            // 30% chance to have a pending disbursement
            if (rand(1, 100) <= 30) {
                $school = $fundingRequest->school;
                $student = $fundingRequest->studentProfile;

                if (!$school || !$student) {
                    continue;
                }

                $amount = rand(500000, 5000000);
                
                Disbursement::create([
                    'funding_request_id' => $fundingRequest->id,
                    'school_id' => $school->id,
                    'student_profile_id' => $student->id,
                    'amount' => $amount,
                    'currency' => 'IDR',
                    'status' => DisbursementStatus::PENDING,
                    'tx_hash' => 'TX' . Str::random(64),
                    'from_address' => $school->stellar_wallet_address,
                    'to_address' => 'G' . Str::random(55) . 'S',
                    'notes' => 'Pencairan dana sedang dalam proses. Menunggu konfirmasi dari blockchain.',
                ]);
            }
        }
    }
}
