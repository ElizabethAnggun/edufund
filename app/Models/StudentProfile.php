<?php

namespace App\Models;

use App\Enums\EducationLevel;
use Illuminate\Support\Facades\Storage;
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
        'academic_rank',
        'date_of_birth',
        'phone',
        'address',
        'bio',
        'student_id_card',
        // High School fields
        'school_name',
        'school_npsn',
        'school_address',
        'class',
        'graduation_year',
        'parent_name',
        'parent_income',
        'student_status',
        // University fields
        'university_name',
        'university_address',
        'faculty',
        'study_program',
        'expected_graduation',
        'scholarship_status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gpa' => 'decimal:2',
            'parent_income' => 'decimal:2',
            'education_level' => EducationLevel::class,
            'graduation_year' => 'integer',
            'expected_graduation' => 'date',
        ];
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return Storage::url($this->profile_photo);
        }
        
        return asset('images/default-avatar.svg');
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

    public function disbursements(): HasMany
    {
        return $this->hasMany(Disbursement::class);
    }
}
