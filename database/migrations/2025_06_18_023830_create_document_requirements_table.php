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
        Schema::create('document_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama yang ditampilkan ke user, e.g., "Scan Kartu Keluarga"
            $table->string('slug')->unique(); // Pengenal unik untuk sistem, e.g., "family_card_scan"
            $table->text('description')->nullable(); // Deskripsi/instruksi, e.g., "Format: PDF, Maks. 2MB"
            $table->boolean('is_mandatory')->default(true); // Apakah dokumen ini wajib secara umum?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requirements');
    }
};