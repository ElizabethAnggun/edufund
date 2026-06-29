<?php

use App\Enums\EducationLevel;
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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('id');
            $table->string('nisn')->nullable()->unique()->after('profile_photo');
            $table->string('education_level')->default(EducationLevel::S1->value)->after('nisn');
            $table->text('bio')->nullable()->after('major');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'nisn', 'education_level', 'bio']);
        });
    }
};
