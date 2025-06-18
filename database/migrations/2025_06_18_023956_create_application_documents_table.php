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
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();

            // Dokumen ini milik pendaftaran yang mana?
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            
            // Jenis dokumen apa yang diupload? (e.g., KTP, Ijazah)
            $table->foreignId('document_requirement_id')->constrained('document_requirements')->onDelete('restrict');

            // Lokasi file yang disimpan di server
            $table->string('file_path');

            // Status verifikasi untuk DOKUMEN INI SAJA
            $table->enum('status', ['pending', 'verified', 'revision_needed', 'rejected'])->default('pending');
            
            // Catatan dari verifikator (jika statusnya revision_needed atau rejected)
            $table->text('notes')->nullable();
            
            // Siapa staf yang memverifikasi dokumen ini?
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};