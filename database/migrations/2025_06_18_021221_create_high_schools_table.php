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
        Schema::create('high_schools', function (Blueprint $table) {
            $table->id();
            $table->string('npsn')->unique()->nullable(); // Nomor Pokok Sekolah Nasional
            $table->string('name'); // Nama Sekolah
            $table->text('address')->nullable(); // Alamat Sekolah
            $table->string('type')->default('SMA'); // Jenis: SMA, SMK, MA
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('high_schools');
    }
};