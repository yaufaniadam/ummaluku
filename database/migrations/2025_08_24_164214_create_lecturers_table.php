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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null'); // Dosen bisa jadi tidak terikat prodi spesifik (misal: rektorat)
            $table->string('nidn')->unique();
            $table->string('full_name_with_degree');
            $table->string('functional_position')->nullable(); // Jabatan Fungsional (e.g., Lektor, Asisten Ahli)
            $table->enum('status', ['active', 'on_leave', 'retired'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
