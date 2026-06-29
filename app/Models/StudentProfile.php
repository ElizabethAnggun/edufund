<?php

namespace App\Models;

use App\Enums\EducationLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'school_id',
        'profile_photo',
        'nisn',
        'nim',
        'education_level',
        'major',
        'semester',
        'gpa',
        'date_of_birth',
        'phone',
        'address',
        'bio',
        'student_id_card',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gpa' => 'decimal:2',
            'education_level' => EducationLevel::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function fundingRequests(): HasMany
    {
        return $this->hasMany(FundingRequest::class);
    }

    public function milestoneSubmissions(): HasMany
    {
        return $this->hasMany(MilestoneSubmission::class);
    }
}
