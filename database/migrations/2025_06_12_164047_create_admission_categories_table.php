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
        Schema::create('admission_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Jalur Prestasi" 
            $table->string('slug')->unique(); // e.g., "prestasi"
            $table->text('description')->nullable(); // Deskripsi singkat jalur pendaftaran 
            $table->unsignedBigInteger('price')->default(0); // Biaya pendaftaran, default 0 
            $table->boolean('is_active')->default(true); // Untuk menonaktifkan/mengaktifkan jalur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_categories');
    }
};