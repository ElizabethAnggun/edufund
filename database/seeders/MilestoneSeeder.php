<?php

namespace Database\Seeders;

use App\Enums\MilestoneStatus;
use App\Models\Campaign;
use App\Models\Milestone;
use Illuminate\Database\Seeder;

class MilestoneSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = Campaign::all();

        $milestoneTemplates = [
            [
                'title' => 'Pembayaran Uang Kuliah Semester 1',
                'description' => 'Pembayaran biaya kuliah semester pertama termasuk SPP, UTS, dan UAS.',
            ],
            [
                'title' => 'Pembayaran Uang Kuliah Semester 2',
                'description' => 'Pembayaran biaya kuliah semester kedua termasuk SPP, UTS, dan UAS.',
            ],
            [
                'title' => 'Pembayaran Uang Kuliah Semester 3',
                'description' => 'Pembayaran biaya kuliah semester ketiga termasuk SPP, UTS, dan UAS.',
            ],
            [
                'title' => 'Pembayaran Uang Kuliah Semester 4',
                'description' => 'Pembayaran biaya kuliah semester keempat termasuk SPP, UTS, dan UAS.',
            ],
            [
                'title' => 'Pembelian Buku dan Alat Tulis',
                'description' => 'Pembelian buku teks, referensi, dan alat tulis untuk semester ini.',
            ],
            [
                'title' => 'Biaya Praktikum Lapangan',
                'description' => 'Biaya transportasi, akomodasi, dan peralatan untuk praktikum lapangan.',
            ],
            [
                'title' => 'Biaya Penelitian Tugas Akhir',
                'description' => 'Biaya survei, wawancara, transportasi, dan analisis data untuk penelitian.',
            ],
            [
                'title' => 'Biaya Seminar dan Konferensi',
                'description' => 'Biaya pendaftaran dan perjalanan untuk mengikuti seminar nasional.',
            ],
        ];

        foreach ($campaigns as $campaign) {
            $numMilestones = rand(3, 5);
            $selectedTemplates = array_rand($milestoneTemplates, $numMilestones);
            
            if (!is_array($selectedTemplates)) {
                $selectedTemplates = [$selectedTemplates];
            }

            $statuses = [
                MilestoneStatus::COMPLETED,
                MilestoneStatus::IN_PROGRESS,
                MilestoneStatus::PENDING,
                MilestoneStatus::PENDING,
            ];

            foreach ($selectedTemplates as $index => $templateIndex) {
                $template = $milestoneTemplates[$templateIndex];
                $status = $statuses[$index] ?? MilestoneStatus::PENDING;
                
                $amount = ($campaign->goal_amount / $numMilestones);
                $dueDate = now()->addMonths(($index + 1) * 3);

                Milestone::firstOrCreate(
                    [
                        'campaign_id' => $campaign->id,
                        'title' => $template['title'],
                    ],
                    [
                        'funding_request_id' => $campaign->funding_request_id,
                        'description' => $template['description'],
                        'amount' => $amount,
                        'due_date' => $dueDate,
                        'status' => $status,
                    ]
                );
            }
        }
    }
}
