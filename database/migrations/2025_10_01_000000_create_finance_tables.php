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
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['income', 'expense']);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('transaction_categories')->onDelete('restrict');
            $table->unsignedBigInteger('amount');
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->enum('type', ['income', 'expense']);
            $table->nullableMorphs('reference'); // reference_id, reference_type
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Creator/Approver
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transaction_categories');
    }
};
