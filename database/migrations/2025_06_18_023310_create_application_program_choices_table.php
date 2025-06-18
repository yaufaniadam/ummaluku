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
        Schema::create('application_program_choices', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke pendaftaran & program studi yang dipilih
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('programs')->onDelete('restrict');
            
            // Urutan pilihan (1 = Pilihan Utama, 2 = Pilihan Kedua, dst.)
            $table->unsignedTinyInteger('choice_order');
            
            // Status kelulusan untuk pilihan prodi ini
            // nullable() karena status baru ada setelah pengumuman
            $table->boolean('is_accepted')->nullable(); 
            
            $table->timestamps();

            // Kunci Unik: Satu pendaftaran tidak boleh memiliki dua pilihan dengan urutan yang sama.
            // Contoh: Tidak boleh ada dua "Pilihan 1" untuk satu pendaftaran.
            $table->unique(['application_id', 'choice_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_program_choices');
    }
};