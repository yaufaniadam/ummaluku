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
        Schema::create('employee_inpassing_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('employee'); // employee_type, employee_id
            $table->foreignId('employee_rank_id')->constrained()->onDelete('cascade');
            
            $table->string('sk_number')->nullable();
            $table->date('sk_date')->nullable()->comment('Tanggal SK');
            $table->date('tmt')->comment('Terhitung Mulai Tanggal');
            
            // Document upload
            $table->string('document_path')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_inpassing_histories');
    }
};
