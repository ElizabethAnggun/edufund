<?php

namespace Database\Seeders;

use App\Enums\AchievementType;
use App\Models\Achievement;
use App\Models\Milestone;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $milestones = Milestone::where('status', 'completed')->get();

        if ($milestones->isEmpty()) {
            return;
        }

        $achievementTemplates = [
            [
                'title' => 'First Milestone Completed',
                'description' => 'Berhasil menyelesaikan milestone pertama dalam kampanye donasi pendidikan.',
                'type' => AchievementType::COMPLETION,
            ],
            [
                'title' => 'Academic Excellence',
                'description' => 'Mendapatkan verifikasi dari sekolah untuk prestasi akademik yang luar biasa.',
                'type' => AchievementType::EXCELLENCE,
            ],
            [
                'title' => 'Campaign Success',
                'description' => 'Kampanye donasi mencapai 100% target funding.',
                'type' => AchievementType::COMPLETION,
            ],
            [
                'title' => 'Documentation Master',
                'description' => 'Berhasil mengupload dan diverifikasi semua dokumen pendukung.',
                'type' => AchievementType::EXCELLENCE,
            ],
            [
                'title' => 'Early Bird',
                'description' => 'Mengajukan permintaan funding lebih awal dari batas waktu.',
                'type' => AchievementType::PARTICIPATION,
            ],
        ];

        foreach ($milestones as $milestone) {
            // 60% chance to have an achievement
            if (rand(1, 100) <= 60) {
                $student = $milestone->fundingRequest->studentProfile;
                
                if (!$student) {
                    continue;
                }

                $template = $achievementTemplates[array_rand($achievementTemplates)];

                Achievement::create([
                    'recipient_id' => $student->user_id,
                    'milestone_id' => $milestone->id,
                    'title' => $template['title'],
                    'description' => $template['description'],
                    'type' => $template['type'],
                    'on_chain_reference' => 'ACH' . strtoupper(\Illuminate\Support\Str::random(30)),
                    'issued_at' => $milestone->updated_at ?? now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}