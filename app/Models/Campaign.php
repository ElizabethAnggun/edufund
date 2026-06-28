<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'funding_request_id',
        'school_id',
        'title',
        'slug',
        'description',
        'goal_amount',
        'currency',
        'current_amount',
        'visibility',
        'status',
        'image',
        'escrow_contract_id',
        'published_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'goal_amount' => 'decimal:2',
            'current_amount' => 'decimal:2',
            'visibility' => CampaignVisibility::class,
            'status' => CampaignStatus::class,
            'published_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function fundingRequest(): BelongsTo
    {
        return $this->belongsTo(FundingRequest::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }
}
