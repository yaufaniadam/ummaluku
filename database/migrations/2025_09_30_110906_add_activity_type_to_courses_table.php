<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('activity_type')->default('kuliah')->after('semester_recommendation');
            // 'kuliah' -> Butuh kelas terjadwal (dosen, ruang, waktu)
            // 'praktikum' -> Sama seperti kuliah, tapi bisa jadi pemicu biaya tambahan
            // 'mandiri' -> Tidak butuh kelas (Skripsi, KKN, Magang)
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('activity_type');
        });
    }
};
pada http://ummaluku.test/admin/akademik/academic-years/1/programs/1/course-classes tambah kelas baru yang akan ditawarkan ke mahsaiswa, supaya tidak menambahkan 1 by 1 kita akan membuatnya dengan 1x klik tombol per mata kuliah.