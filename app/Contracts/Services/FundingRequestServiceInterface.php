<?php

namespace App\Contracts\Services;

use App\Models\FundingRequest;
use App\Models\StudentProfile;
use Illuminate\Pagination\LengthAwarePaginator;

interface FundingRequestServiceInterface
{
    public function getAllByStudent(StudentProfile $student, int $perPage = 10): LengthAwarePaginator;
    public function create(StudentProfile $student, array $data): FundingRequest;
    public function update(FundingRequest $request, array $data): FundingRequest;
    public function delete(FundingRequest $request): bool;
    public function submit(FundingRequest $request): FundingRequest;
}
