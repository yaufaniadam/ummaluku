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
    Schema::create('curriculum_course', function (Blueprint $table) {
        $table->id();
        $table->foreignId('curriculum_id')->constrained('curriculums')->onDelete('cascade');
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        // Tambahkan kolom lain jika perlu, misal: semester, status (wajib/pilihan)
        // Untuk saat ini kita buat sederhana dulu
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_course');
    }
};
