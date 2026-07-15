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
        Schema::table('schools', function (Blueprint $table) {
            $table->string('npsn')->after('name')->nullable();
            $table->string('headmaster_name')->after('npsn')->nullable();
            $table->string('logo')->after('headmaster_name')->nullable();
            $table->string('stellar_wallet_address')->after('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['npsn', 'headmaster_name', 'logo', 'stellar_wallet_address']);
        });
    }
};
