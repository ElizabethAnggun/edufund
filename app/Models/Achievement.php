<?php

namespace App\Models;

use App\Enums\AchievementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'milestone_id',
        'title',
        'description',
        'type',
        'on_chain_reference',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => AchievementType::class,
            'issued_at' => 'datetime',
        ];
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function blockchainTransaction(): MorphOne
    {
        return $this->morphOne(BlockchainTransaction::class, 'transactionable');
    }
}
