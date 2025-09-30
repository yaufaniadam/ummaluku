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
        Schema::table('curriculum_course', function (Blueprint $table) {
            // Kolom untuk menentukan di semester berapa MK ini ditempatkan dalam kurikulum
            $table->unsignedTinyInteger('semester')->after('course_id');

            // Kolom untuk menentukan sifat MK dalam kurikulum ini
            $table->enum('type', ['Wajib', 'Pilihan'])->default('Wajib')->after('semester');
        });
    }

    public function down(): void
    {
        Schema::table('curriculum_course', function (Blueprint $table) {
            $table->dropColumn(['semester', 'type']);
        });
    }
};
