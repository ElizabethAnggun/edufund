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

        foreach ($campaigns as $campaign) {
            $milestones = [
                [
                    'funding_request_id' => $campaign->funding_request_id,
                    'title' => 'Pembayaran Semester 1',
                    'description' => 'Biaya kuliah semester pertama.',
                    'amount' => $campaign->goal_amount * 0.25,
                    'due_date' => now()->addMonths(3),
                    'status' => MilestoneStatus::COMPLETED,
                ],
                [
                    'funding_request_id' => $campaign->funding_request_id,
                    'title' => 'Pembayaran Semester 2',
                    'description' => 'Biaya kuliah semester kedua.',
                    'amount' => $campaign->goal_amount * 0.25,
                    'due_date' => now()->addMonths(6),
                    'status' => MilestoneStatus::IN_PROGRESS,
                ],
                [
                    'funding_request_id' => $campaign->funding_request_id,
                    'title' => 'Pembayaran Semester 3',
                    'description' => 'Biaya kuliah semester ketiga.',
                    'amount' => $campaign->goal_amount * 0.25,
                    'due_date' => now()->addMonths(9),
                    'status' => MilestoneStatus::PENDING,
                ],
                [
                    'funding_request_id' => $campaign->funding_request_id,
                    'title' => 'Pembayaran Semester 4',
                    'description' => 'Biaya kuliah semester keempat.',
                    'amount' => $campaign->goal_amount * 0.25,
                    'due_date' => now()->addMonths(12),
                    'status' => MilestoneStatus::PENDING,
                ],
            ];

            foreach ($milestones as $milestoneData) {
                Milestone::firstOrCreate(
                    ['campaign_id' => $campaign->id, 'title' => $milestoneData['title']],
                    $milestoneData
                );
            }
        }
    }
}
