<?php

namespace Database\Seeders;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Enums\FundingRequestStatus;
use App\Models\Campaign;
use App\Models\FundingRequest;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $fundingRequests = FundingRequest::whereIn('status', [
            \App\Enums\FundingRequestStatus::APPROVED,
            \App\Enums\FundingRequestStatus::ACTIVE,
            \App\Enums\FundingRequestStatus::COMPLETED,
        ])->get();

        $campaignImages = [
            'campaigns/education1.jpg',
            'campaigns/education2.jpg',
            'campaigns/education3.jpg',
            'campaigns/research1.jpg',
            'campaigns/books1.jpg',
        ];

        $descriptions = [
            'Bantu {name} menyelesaikan pendidikannya di {major}. Setiap kontribusi, baik besar maupun kecil, akan membantu mewujudkan mimpi {name} untuk menjadi profesional di bidang {major}.',
            'Kami membutuhkan bantuan Anda untuk mendukung biaya pendidikan {name}. Dengan donasi Anda, {name} dapat fokus belajar tanpa khawatir tentang biaya hidup.',
            'Mari kita bantu {name} meraih cita-cita. Dana ini akan digunakan untuk biaya kuliah, buku, dan kebutuhan akademik selama semester ini.',
            'Donasi Anda akan langsung membantu {name} yang sedang kesulitan biaya pendidikan. Bersama kita bisa改变 hidup seseorang.',
        ];

        foreach ($fundingRequests as $index => $request) {
            $student = $request->studentProfile;
            $school = $request->school;
            
            if (!$student || !$school) {
                continue;
            }

            $name = $student->user->name;
            $major = $student->major;
            $description = $descriptions[$index % count($descriptions)];
            $description = str_replace(['{name}', '{major}'], [$name, $major], $description);

            $goalAmount = $request->total_amount;
            $progressPercentage = rand(20, 100);
            $currentAmount = ($goalAmount * $progressPercentage) / 100;

            $status = match(true) {
                $progressPercentage >= 100 => CampaignStatus::COMPLETED,
                $progressPercentage >= 50 => CampaignStatus::ACTIVE,
                default => CampaignStatus::ACTIVE,
            };

            $publishedAt = $request->submitted_at ?? now()->subDays(rand(10, 60));
            $endsAt = now()->addDays(rand(30, 90));

            $slug = \Illuminate\Support\Str::slug('kampanye-bantuan-' . strtolower(str_replace(' ', '-', $name))) . '-' . $request->id;
            
            Campaign::updateOrCreate(
                [
                    'funding_request_id' => $request->id,
                    'school_id' => $school->id,
                ],
                [
                    'title' => 'Kampanye Bantuan ' . $name,
                    'slug' => $slug,
                    'description' => $description,
                    'goal_amount' => $goalAmount,
                    'current_amount' => $currentAmount,
                    'currency' => 'IDR',
                    'visibility' => CampaignVisibility::PUBLIC,
                    'status' => $status,
                    'image' => $campaignImages[$index % count($campaignImages)],
                    'escrow_contract_id' => 'ESCROW' . strtoupper(\Illuminate\Support\Str::random(20)),
                    'published_at' => $publishedAt,
                    'ends_at' => $endsAt,
                ]
            );
        }
    }
}
