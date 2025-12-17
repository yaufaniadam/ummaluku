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
        Schema::create('work_unit_officials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_unit_id')->constrained('work_units')->cascadeOnDelete();
            $table->string('employee_type');
            $table->unsignedBigInteger('employee_id');
            $table->string('position'); // e.g., 'Kepala'
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('sk_number')->nullable();
            $table->timestamps();

            $table->index(['employee_type', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_unit_officials');
    }
};
