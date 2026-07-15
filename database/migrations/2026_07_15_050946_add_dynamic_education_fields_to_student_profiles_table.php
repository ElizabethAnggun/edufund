<?php

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
            // Make existing university-specific fields nullable
            $table->string('nim')->nullable()->change();
            $table->string('major')->nullable()->change();
            $table->integer('semester')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->date('date_of_birth')->nullable()->change();

            // High School (SMA/SMK) fields
            $table->string('school_name')->nullable()->after('education_level');
            $table->string('school_npsn')->nullable()->after('school_name');
            $table->text('school_address')->nullable()->after('school_npsn');
            $table->string('class')->nullable()->after('school_address'); // 'class' is reserved, use backticks
            $table->year('graduation_year')->nullable()->after('class');
            $table->string('parent_name')->nullable()->after('graduation_year');
            $table->decimal('parent_income', 15, 2)->nullable()->after('parent_name');
            $table->string('student_status')->nullable()->after('parent_income');

            // University fields
            $table->string('university_name')->nullable()->after('student_status');
            $table->text('university_address')->nullable()->after('university_name');
            $table->string('faculty')->nullable()->after('university_address');
            $table->string('study_program')->nullable()->after('faculty');
            $table->date('expected_graduation')->nullable()->after('study_program');
            $table->string('scholarship_status')->nullable()->after('expected_graduation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn([
                'school_name',
                'school_npsn',
                'school_address',
                'class',
                'graduation_year',
                'parent_name',
                'parent_income',
                'student_status',
                'university_name',
                'university_address',
                'faculty',
                'study_program',
                'expected_graduation',
                'scholarship_status',
            ]);

            // Revert nullable fields to not nullable
            $table->string('nim')->nullable(false)->change();
            $table->string('major')->nullable(false)->change();
            $table->integer('semester')->default(1)->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->date('date_of_birth')->nullable(false)->change();
        });
    }
};
