<?php

namespace App\Models;

use App\Enums\DonationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donor_id',
        'campaign_id',
        'amount',
        'currency',
        'status',
        'message',
        'anonymous',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => DonationStatus::class,
            'anonymous' => 'boolean',
        ];
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function blockchainTransaction(): MorphOne
    {
        return $this->morphOne(BlockchainTransaction::class, 'transactionable');
    }
}
