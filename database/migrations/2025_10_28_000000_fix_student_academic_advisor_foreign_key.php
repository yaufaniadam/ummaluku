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
        Schema::table('students', function (Blueprint $table) {
            // Drop foreign key ke users
            $table->dropForeign('students_academic_advisor_id_foreign');

            // Tambahkan foreign key baru ke lecturers
            // Perlu memastikan academic_advisor_id memiliki tipe yang sama dengan id di lecturers (bigint unsigned)
            // Karena sudah dibuat sebelumnya, tipe data seharusnya sudah benar.
            $table->foreign('academic_advisor_id')
                  ->references('id')
                  ->on('lecturers')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop foreign key ke lecturers
            $table->dropForeign(['academic_advisor_id']);

            // Kembalikan foreign key ke users
            $table->foreign('academic_advisor_id', 'students_academic_advisor_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};
