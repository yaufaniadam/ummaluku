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
        Schema::create('prospectives', function (Blueprint $table) {
            $table->id();
            
            // Relasi One-to-One dengan tabel users.
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            // Nomor Identitas
            $table->string('id_number')->unique()->nullable(); // NIK KTP
            $table->string('nisn')->unique()->nullable(); // Nomor Induk Siswa Nasional
            
            // Biodata Tambahan
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // Data Keluarga
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();

            // Relasi ke tabel master lain
            $table->foreignId('religion_id')->nullable()->constrained('religions')->onDelete('set null');
            $table->foreignId('high_school_id')->nullable()->constrained('high_schools')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospectives');
    }
};