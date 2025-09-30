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
        Schema::create('academic_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_invoice_id')->constrained()->onDelete('cascade');
            $table->date('payment_date');
            $table->unsignedBigInteger('amount');
            $table->string('proof_url'); // Path ke file bukti bayar yang diupload
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); // ID admin/staf keuangan
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_payments');
    }
};
