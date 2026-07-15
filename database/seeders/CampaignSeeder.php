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
        $fundingRequests = FundingRequest::where('status', '!=', FundingRequestStatus::PENDING_SCHOOL_APPROVAL)->get();

        foreach ($fundingRequests as $index => $request) {
            Campaign::firstOrCreate(
                ['funding_request_id' => $request->id],
                [
                    'title' => 'Kampanye Bantuan ' . $request->studentProfile->user->name,
                    'slug' => \Illuminate\Support\Str::slug('Kampanye Bantuan ' . $request->studentProfile->user->name),
                    'description' => 'Bantu ' . $request->studentProfile->user->name . ' menyelesaikan pendidikannya di ' . $request->studentProfile->major . '.',
                    'goal_amount' => $request->total_amount,
                    'current_amount' => $index === 0 ? $request->total_amount * 0.7 : $request->total_amount,
                    'status' => $index === 0 ? CampaignStatus::ACTIVE : CampaignStatus::COMPLETED,
                    'visibility' => CampaignVisibility::PUBLIC,
                ]
            );
        }
    }
}
