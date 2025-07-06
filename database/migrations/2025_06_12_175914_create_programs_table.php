<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');
            $table->string('name_id'); // Nama dalam Bahasa Indonesia
            $table->string('name_en')->nullable(); // Nama dalam Bahasa Inggris
            $table->string('degree'); // Jenjang, e.g., "S1", "S2", "D3"
            $table->string('code', 4)->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};