<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nip')->unique();
            $table->enum('gender', ['L', 'P']);
            $table->string('phone')->nullable();
            $table->text('address')->nullable();

            // Nullable because a staff might belong to a Bureau (WorkUnit) OR a Study Program (Program)
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null');
            $table->foreignId('work_unit_id')->nullable()->constrained('work_units')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
