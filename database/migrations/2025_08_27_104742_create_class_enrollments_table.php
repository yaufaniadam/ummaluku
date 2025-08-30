<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            
            // Untuk alur persetujuan KRS oleh Dosen PA
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('lecturers')->onDelete('set null');

            // Kolom ini akan diisi oleh Dosen Pengampu di akhir semester
            $table->decimal('grade_score', 5, 2)->nullable(); // Nilai angka (e.g., 85.50)
            $table->string('grade_letter', 3)->nullable();   // Nilai huruf (e.g., 'A', 'B+')
            $table->decimal('grade_index', 3, 2)->nullable();  // Bobot nilai (e.g., 4.00, 3.50)

            $table->timestamps();

            // Constraint agar mahasiswa tidak bisa mendaftar di kelas yang sama lebih dari sekali
            $table->unique(['student_id', 'course_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
    }
};
