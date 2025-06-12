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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Gelombang 1", "Gelombang Khusus"
            $table->year('year'); // Tahun ajaran, e.g., 2025
            $table->date('start_date'); // Tanggal mulai pendaftaran
            $table->date('end_date'); // Tanggal akhir pendaftaran
            $table->boolean('is_active')->default(false); // Hanya satu gelombang yang boleh aktif dalam satu waktu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};