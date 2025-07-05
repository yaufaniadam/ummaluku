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
            $table->string('satuanPendidikanId')->unique()->nullable(); // id https://api.data.belajar.id/
            $table->string('name'); // Nama Sekolah
            $table->string('type')->default('SMA SEDERAJAT'); // Jenis: SMA, SMK, MA
            $table->text('address')->nullable(); // Alamat Sekolah
            $table->string('village')->nullable(); // nama desa
            $table->string('city')->nullable(); // nama desa
            $table->string('province')->nullable(); // nama desa
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