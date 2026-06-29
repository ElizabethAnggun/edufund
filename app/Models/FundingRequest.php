<?php

namespace App\Models;

use App\Enums\FundingCategory;
use App\Enums\FundingRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FundingRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_profile_id',
        'school_id',
        'title',
        'description',
        'total_amount',
        'currency',
        'deadline',
        'funding_category',
        'purpose',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'status' => FundingRequestStatus::class,
            'funding_category' => FundingCategory::class,
            'deadline' => 'date',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function campaign(): HasOne
    {
        return $this->hasOne(Campaign::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function supportingDocuments(): HasMany
    {
        return $this->hasMany(SupportingDocument::class);
    }
}
