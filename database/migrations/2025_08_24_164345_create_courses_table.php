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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained('curriculums')->onDelete('cascade');
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->unsignedTinyInteger('sks');
            $table->unsignedTinyInteger('semester_recommendation'); // Rekomendasi diambil di semester ke-
            $table->enum('type', ['Wajib', 'Pilihan', 'Wajib Peminatan'])->default('Wajib');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
