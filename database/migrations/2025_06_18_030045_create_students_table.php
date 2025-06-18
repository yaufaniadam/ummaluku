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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            
            // Terhubung ke akun user
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            // Terhubung ke program studi yang diterima
            $table->foreignId('program_id')->constrained('programs')->onDelete('restrict');
            
            $table->string('nim')->unique(); // Nomor Induk Mahasiswa
            $table->year('entry_year'); // Tahun Angkatan/Masuk
            
            // Status kemahasiswaan
            $table->string('status')->default('Aktif'); // e.g., 'Aktif', 'Cuti', 'Lulus', 'DO'
            
            // Relasi ke Dosen Pembimbing Akademik (bisa diisi nanti)
            // Menggunakan `users` karena Dosen adalah User dengan peran 'Pegawai/Dosen'
            $table->foreignId('academic_advisor_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};