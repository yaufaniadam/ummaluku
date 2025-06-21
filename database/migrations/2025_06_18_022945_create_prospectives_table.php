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

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            // Relasi One-to-One dengan tabel users.
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            // Nomor Identitas
            $table->string('id_number')->unique()->nullable(); // NIK KTP
            $table->string('nisn')->unique()->nullable(); // Nomor Induk Siswa Nasional
            $table->string('npwp', 20)->nullable();
            $table->boolean('is_kps_recipient')->default(false)->comment('Penerima Kartu Perlindungan Sosial');

            // Biodata Tambahan
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code', 10)->nullable();


            // Data Keluarga
            $table->string('father_name')->nullable();
            $table->string('father_nik', 20)->nullable();
            $table->unsignedBigInteger('father_income')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_nik', 20)->nullable();
            $table->unsignedBigInteger('mother_income')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('parent_phone')->nullable();

            // Kolom wali tetap sama
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_income')->nullable();

            // Relasi ke tabel master lain
            $table->foreignId('religion_id')->nullable()->constrained('religions')->onDelete('set null');
            $table->foreignId('high_school_id')->nullable()->constrained('high_schools')->onDelete('set null');

            // Gunakan char/string sesuai tipe kolom 'code' di laravolt
            $table->char('province_code', 2)->nullable();
            $table->char('city_code', 4)->nullable();
            $table->char('district_code', 7)->nullable();
            $table->char('village_code', 10)->nullable();

            $table->timestamps();
            // Foreign key harus match tipe dan kolasinya
            $table->foreign('province_code')->references('code')->on('indonesia_provinces')->onDelete('set null');
            $table->foreign('city_code')->references('code')->on('indonesia_cities')->onDelete('set null');
            $table->foreign('district_code')->references('code')->on('indonesia_districts')->onDelete('set null');
            $table->foreign('village_code')->references('code')->on('indonesia_villages')->onDelete('set null');
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
