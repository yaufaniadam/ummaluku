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
        Schema::rename('program_heads', 'program_officials');

        Schema::table('program_officials', function (Blueprint $table) {
            $table->string('position')->default('Kaprodi')->after('lecturer_id'); // 'Kaprodi', 'Sekretaris'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_officials', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::rename('program_officials', 'program_heads');
    }
};
