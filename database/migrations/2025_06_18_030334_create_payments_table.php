<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Pembayaran ini untuk invoice yang mana
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->bigInteger('amount_paid'); // Jumlah yang dibayarkan
            $table->timestamp('paid_at');
            $table->string('payment_method'); // e.g., "Bank Transfer", "Cash"
            $table->text('notes')->nullable(); // e.g., "Cicilan pertama"
            // Staf yang memverifikasi pembayaran (jika prosesnya manual)
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};