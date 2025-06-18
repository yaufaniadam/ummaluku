<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_category_batch', function (Blueprint $table) {
            $table->foreignId('admission_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->primary(['admission_category_id', 'batch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_category_batch');
    }
};