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
        Schema::create('academic_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_invoice_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->unsignedBigInteger('amount');
            $table->json('details')->nullable(); // Untuk menyimpan detail (misal: 21 SKS x 150.000)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_invoice_items');
    }
};
