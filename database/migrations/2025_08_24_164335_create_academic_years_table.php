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
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('year_code', 5)->unique(); // e.g., '20251' (2025 Ganjil), '20252' (2025 Genap)
            $table->string('name'); // e.g., 'Semester Ganjil 2025/2026'
            $table->enum('semester_type', ['Ganjil', 'Genap']);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('krs_start_date');
            $table->date('krs_end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
