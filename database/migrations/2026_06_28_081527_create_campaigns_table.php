<?php

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funding_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('goal_amount', 15, 2);
            $table->string('currency')->default('XLM');
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->string('visibility')->default(CampaignVisibility::PUBLIC->value);
            $table->string('status')->default(CampaignStatus::DRAFT->value);
            $table->string('image')->nullable();
            $table->string('escrow_contract_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('funding_request_id');
            $table->index('school_id');
            $table->index('status');
            $table->index('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
