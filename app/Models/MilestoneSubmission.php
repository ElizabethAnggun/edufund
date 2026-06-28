<?php

namespace App\Models;

use App\Enums\MilestoneSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MilestoneSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'milestone_id',
        'student_profile_id',
        'description',
        'status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => MilestoneSubmissionStatus::class,
            'submitted_at' => 'datetime',
        ];
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function milestoneSubmissionDocuments(): HasMany
    {
        return $this->hasMany(MilestoneSubmissionDocument::class);
    }

    public function verification(): MorphOne
    {
        return $this->morphOne(Verification::class, 'verifiable');
    }
}
