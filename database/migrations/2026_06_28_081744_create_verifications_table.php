<?php

use App\Enums\VerificationStatus;
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
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('verifiable');
            $table->foreignId('verifier_id')->constrained('users')->cascadeOnDelete();
            $table->string('status');
            $table->text('feedback')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['verifiable_id', 'verifiable_type']);
            $table->index('verifier_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
