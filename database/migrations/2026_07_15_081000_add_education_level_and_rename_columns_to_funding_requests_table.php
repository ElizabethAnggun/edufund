<?php

use App\Enums\EducationLevel;
use App\Enums\FundingCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('funding_requests', function (Blueprint $table) {
            $table->string('education_level')->default(EducationLevel::S1->value)->after('total_amount');
            $table->renameColumn('funding_category', 'category');
            $table->renameColumn('approved_at', 'school_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('funding_requests', function (Blueprint $table) {
            $table->dropColumn('education_level');
            $table->renameColumn('category', 'funding_category');
            $table->renameColumn('school_approved_at', 'approved_at');
        });
    }
};
