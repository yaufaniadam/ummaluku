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
        Schema::table('prospectives', function (Blueprint $table) {
            // Menambah kolom baru setelah kolom 'user_id'
            // Default 'pmb_online' untuk semua pendaftar baru ke depannya
            $table->string('registration_source')->after('user_id')->default('pmb_online');
        });
    }

    public function down(): void
    {
        Schema::table('prospectives', function (Blueprint $table) {
            $table->dropColumn('registration_source');
        });
    }
};
