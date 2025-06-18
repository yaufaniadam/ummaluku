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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique(); // Nomor pendaftaran unik, e.g., PMB2025-0001
            
            // Relasi ke tabel-tabel lain
            $table->foreignId('prospective_id')->constrained('prospectives')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('batches')->onDelete('restrict');
            $table->foreignId('admission_category_id')->constrained('admission_categories')->onDelete('restrict');
            
            // Kolom status utama yang melacak progres pendaftar
            $table->string('status')->default('awaiting_payment'); // Contoh status awal
            
            // Kolom untuk informasi tambahan
            $table->boolean('is_fee_free')->default(false); // Jika dapat gratis biaya pendaftaran 
            $table->text('revision_notes')->nullable(); // Catatan dari admin jika dokumen perlu direvisi
            $table->text('rejection_reason')->nullable(); // Alasan jika pendaftaran ditolak
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};