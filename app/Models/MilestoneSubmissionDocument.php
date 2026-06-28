<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class MilestoneSubmissionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_submission_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    public function milestoneSubmission(): BelongsTo
    {
        return $this->belongsTo(MilestoneSubmission::class);
    }
}
