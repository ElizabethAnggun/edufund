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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nim');
            $table->string('major');
            $table->integer('semester')->default(1);
            $table->decimal('gpa', 3, 2)->nullable();
            $table->date('date_of_birth');
            $table->string('phone');
            $table->text('address');
            $table->string('student_id_card')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('user_id');
            $table->unique('nim');
            $table->index('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
