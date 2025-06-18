<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // Tagihan ini milik mahasiswa (student) mana
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('title'); // e.g., "Tagihan Registrasi Semester Gasal 2025"
            $table->bigInteger('total_amount');
            $table->date('due_date');
            $table->string('status')->default('unpaid'); // unpaid, paid, overdue, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};