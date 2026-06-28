<?php

namespace App\Models;

use App\Enums\BlockchainNetwork;
use App\Enums\BlockchainTransactionStatus;
use App\Enums\BlockchainTransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;

class BlockchainTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transactionable_id',
        'transactionable_type',
        'user_id',
        'tx_hash',
        'type',
        'amount',
        'currency',
        'from_address',
        'to_address',
        'status',
        'network',
        'error_message',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'type' => BlockchainTransactionType::class,
            'status' => BlockchainTransactionStatus::class,
            'network' => BlockchainNetwork::class,
            'confirmed_at' => 'datetime',
        ];
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
