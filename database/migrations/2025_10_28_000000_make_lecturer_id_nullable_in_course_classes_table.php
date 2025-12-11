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
        Schema::table('course_classes', function (Blueprint $table) {
            $table->foreignId('lecturer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_classes', function (Blueprint $table) {
            // Kita tidak bisa dengan mudah mengembalikan ke nullable(false) jika sudah ada data NULL.
            // Namun untuk definisi migrasi, kita tuliskan definisinya.
            // Asumsi: sebelum rollback, data sudah dibersihkan/diisi.
            $table->foreignId('lecturer_id')->nullable(false)->change();
        });
    }
};
