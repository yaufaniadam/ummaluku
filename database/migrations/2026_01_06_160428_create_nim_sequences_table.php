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
        Schema::create('nim_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->integer('entry_year');
            $table->integer('last_sequence')->default(0);
            $table->timestamps();
            
            // Unique constraint to prevent duplicate sequences
            $table->unique(['program_id', 'entry_year']);
            
            // Foreign key constraint
            $table->foreign('program_id')
                  ->references('id')
                  ->on('programs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nim_sequences');
    }
};
