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
        Schema::table('class_enrollments', function (Blueprint $table) {
            // Updating status enum to include 'approved_advisor'
            // Using DB::statement because standard schema builders struggle with ENUM changes on some drivers.
            // But we will use change() if possible, or raw sql.
            // Since this is likely MySQL, we can redefine the column.

            // Note: approved_by is already nullable foreign key to lecturers.

             $table->enum('status', ['pending', 'approved_advisor', 'approved', 'rejected'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('class_enrollments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
};
