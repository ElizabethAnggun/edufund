<?php

namespace Database\Seeders;

use App\Contracts\Services\DisbursementServiceInterface;
use App\Models\Milestone;
use Illuminate\Database\Seeder;

class DisbursementSeeder extends Seeder
{
    /**
     * Seed a sample school -> student fund disbursement for a completed
     * milestone, demonstrating the integrated transaction flow.
     */
    public function run(): void
    {
        $milestone = Milestone::where('status', 'completed')
            ->whereDoesntHave('disbursement')
            ->first();

        if (!$milestone) {
            return;
        }

        $school = $milestone->fundingRequest->school;

        if (!$school) {
            return;
        }

        $disbursementService = app(DisbursementServiceInterface::class);
        $disbursementService->release($school, $milestone, 'Disbursement seeded for demonstration.');
    }
}
