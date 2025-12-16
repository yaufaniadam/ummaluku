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
        Schema::create('faculty_officials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');

            // Polymorphic relation to handle both Lecturers (Dekan) and Staff (KTU, Kabag)
            $table->morphs('employee');

            // 'Dekan', 'Kepala Tata Usaha', 'Kepala Bagian Administrasi Akademik'
            $table->string('position');

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('sk_number')->nullable(); // Nomor SK Pengangkatan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_officials');
    }
};
