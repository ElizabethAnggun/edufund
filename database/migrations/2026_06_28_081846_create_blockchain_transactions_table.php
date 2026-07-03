<?php

use App\Enums\BlockchainNetwork;
use App\Enums\BlockchainTransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blockchain_transactions', function (Blueprint $table) {
            $table->id();

            // Morph relationship (manual agar nama index tidak melebihi 64 karakter)
            $table->unsignedBigInteger('transactionable_id');
            $table->string('transactionable_type');

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('tx_hash')->unique();
            $table->string('type');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('XLM');
            $table->string('from_address');
            $table->string('to_address');

            $table->string('status')
                ->default(BlockchainTransactionStatus::PENDING->value);

            $table->string('network')
                ->default(BlockchainNetwork::TESTNET->value);

            $table->text('error_message')->nullable();
            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();

            // Custom index dengan nama pendek
            $table->index(
                ['transactionable_type', 'transactionable_id'],
                'trxable_idx'
            );

            $table->index('user_id');
            $table->index('status');
            $table->index('network');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blockchain_transactions');
    }
};