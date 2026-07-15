<?php

namespace App\Contracts\Services;

use App\Models\Disbursement;
use App\Models\Milestone;
use App\Models\School;
use App\Models\StudentProfile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DisbursementServiceInterface
{
    public function release(School $school, Milestone $milestone, ?string $notes = null): Disbursement;
    public function getBySchool(School $school, int $perPage = 15): LengthAwarePaginator;
    public function getByStudent(StudentProfile $student): Collection;
}
