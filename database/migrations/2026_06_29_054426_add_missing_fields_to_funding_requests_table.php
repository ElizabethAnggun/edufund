<?php

use App\Enums\FundingCategory;
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
        Schema::table('funding_requests', function (Blueprint $table) {
            $table->date('deadline')->after('total_amount');
            $table->string('funding_category')->default(FundingCategory::TUITION->value)->after('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('funding_requests', function (Blueprint $table) {
            $table->dropColumn(['deadline', 'funding_category']);
        });
    }
};
