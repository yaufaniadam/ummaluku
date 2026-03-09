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
        Schema::create('education_histories', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relation to Staff or Lecturer
            $table->morphs('employee');
            
            // Education details
            $table->enum('education_level', ['SD', 'SMP', 'SMA', 'D3', 'D4', 'S1', 'S2', 'S3']);
            $table->string('institution_name');
            $table->year('graduation_year');
            $table->string('major')->nullable();
            
            // Certificate upload (PDF only)
            $table->string('certificate_path')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_histories');
    }
};
