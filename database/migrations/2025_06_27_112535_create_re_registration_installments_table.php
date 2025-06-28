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
        Schema::create('re_registration_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('re_registration_invoice_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('installment_number'); // Cicilan ke-1, ke-2, dst.
            $table->unsignedBigInteger('amount'); // Jumlah cicilan ini
            $table->date('due_date'); // Batas waktu bayar cicilan ini
            $table->string('status')->default('unpaid'); // unpaid, pending_verification, paid
            $table->string('proof_of_payment')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('re_registration_installments');
    }
};
