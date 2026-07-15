<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blockchain_transactions', function (Blueprint $table) {
            $table->dropUnique(['tx_hash']);
            $table->string('tx_hash')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('blockchain_transactions', function (Blueprint $table) {
            $table->string('tx_hash')->unique()->change();
        });
    }
};
