<?php

namespace App\Contracts\Services;

use App\Models\Milestone;
use Illuminate\Support\Collection;

interface MilestoneServiceInterface
{
    public function getAllByStudent($student): Collection;
    public function submitForVerification(Milestone $milestone): void;
}