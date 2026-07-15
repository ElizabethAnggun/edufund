<?php

namespace App\Services;

use App\Contracts\Services\FundingRequestServiceInterface;
use App\Contracts\Services\StellarServiceInterface;
use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Enums\FundingRequestStatus;
use App\Models\Campaign;
use App\Models\FundingRequest;
use App\Models\StudentProfile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FundingRequestService implements FundingRequestServiceInterface
{
    public function __construct(
        private readonly StellarServiceInterface $stellarService
    ) {}

    public function getAllByStudent(StudentProfile $student, int $perPage = 10): LengthAwarePaginator
    {
        return $student->fundingRequests()->latest()->paginate($perPage);
    }

    public function create(StudentProfile $student, array $data): FundingRequest
    {
        return $student->fundingRequests()->create([
            'school_id' => $student->school_id,
            'title' => $data['title'],
            'description' => $data['description'],
            'total_amount' => $data['total_amount'],
            'currency' => $data['currency'] ?? 'XLM',
            'deadline' => $data['deadline'],
            'category' => $data['category'],
            'purpose' => $data['purpose'] ?? $data['description'],
            'status' => FundingRequestStatus::DRAFT,
        ]);
    }

    public function update(FundingRequest $request, array $data): FundingRequest
    {
        $request->update([
            'title' => $data['title'] ?? $request->title,
            'description' => $data['description'] ?? $request->description,
            'total_amount' => $data['total_amount'] ?? $request->total_amount,
            'deadline' => $data['deadline'] ?? $request->deadline,
            'category' => $data['category'] ?? $request->category,
            'purpose' => $data['purpose'] ?? $data['description'] ?? $request->purpose,
        ]);

        return $request;
    }

    public function delete(FundingRequest $request): bool
    {
        $request->supportingDocuments()->each(function ($doc) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        });

        return $request->delete();
    }

    public function submit(FundingRequest $request): FundingRequest
    {
        $request->update([
            'status' => FundingRequestStatus::PENDING_SCHOOL_APPROVAL,
            'submitted_at' => now(),
        ]);

        return $request;
    }

    public function approve(FundingRequest $request): FundingRequest
    {
        return DB::transaction(function () use ($request) {
            $request->update([
                'status' => FundingRequestStatus::APPROVED,
                'school_approved_at' => now(),
                'rejected_at' => null,
                'rejection_reason' => null,
            ]);

            $campaign = $this->createCampaignForRequest($request);

            // Create escrow account on Stellar/Soroban for this campaign
            try {
                $escrowId = $this->stellarService->createEscrowAccount($campaign);
                Log::info('Escrow created for campaign', [
                    'campaign_id' => $campaign->id,
                    'escrow_id' => $escrowId,
                ]);
            } catch (\Exception $e) {
                Log::warning('Escrow creation failed (non-critical)', [
                    'campaign_id' => $campaign->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return $request;
        });
    }

    public function reject(FundingRequest $request, string $reason): FundingRequest
    {
        return DB::transaction(function () use ($request, $reason) {
            $request->update([
                'status' => FundingRequestStatus::REJECTED,
                'rejected_at' => now(),
                'school_approved_at' => null,
                'rejection_reason' => $reason,
            ]);

            return $request;
        });
    }

    /**
     * Create (or return the existing) campaign for an approved funding request.
     * This links the student's request to a public fundraising campaign owned
     * by the school, closing the student -> donor transaction loop.
     * Also creates an escrow account on Stellar/Soroban for transparent fund management.
     */
    public function createCampaignForRequest(FundingRequest $request): Campaign
    {
        $campaign = Campaign::firstOrCreate(
            ['funding_request_id' => $request->id],
            [
                'school_id' => $request->school_id,
                'title' => 'Kampanye Bantuan ' . $request->studentProfile->user->name,
                'slug' => Str::slug(
                    'kampanye-bantuan-' . $request->studentProfile->user->name . '-' . $request->id
                ),
                'description' => 'Bantu ' . $request->studentProfile->user->name
                    . ' ' . ($request->purpose ?? $request->description),
                'goal_amount' => $request->total_amount,
                'current_amount' => 0,
                'status' => CampaignStatus::ACTIVE,
                'visibility' => CampaignVisibility::PUBLIC,
                'published_at' => now(),
            ]
        );

        return $campaign;
    }
}
