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
        Schema::create('admission_category_document_requirement', function (Blueprint $table) {
            // Kita tetap membuat kolom seperti biasa
            $table->foreignId('admission_category_id');
            $table->foreignId('document_requirement_id');

            // Kita definisikan foreign key secara manual dengan NAMA YANG LEBIH PENDEK
            $table->foreign('admission_category_id', 'admission_category_foreign')
                  ->references('id')
                  ->on('admission_categories')
                  ->onDelete('cascade');

            $table->foreign('document_requirement_id', 'document_requirement_foreign')
                  ->references('id')
                  ->on('document_requirements')
                  ->onDelete('cascade');

            // Primary key tetap sama
            $table->primary(['admission_category_id', 'document_requirement_id'], 'admission_req_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_category_document_requirement');
    }
};