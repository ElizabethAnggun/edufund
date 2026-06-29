<?php

namespace App\Services;

use App\Contracts\Services\FundingRequestServiceInterface;
use App\Enums\FundingRequestStatus;
use App\Models\FundingRequest;
use App\Models\StudentProfile;
use Illuminate\Pagination\LengthAwarePaginator;

class FundingRequestService implements FundingRequestServiceInterface
{
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
            'funding_category' => $data['funding_category'],
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
            'funding_category' => $data['funding_category'] ?? $request->funding_category,
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
}
