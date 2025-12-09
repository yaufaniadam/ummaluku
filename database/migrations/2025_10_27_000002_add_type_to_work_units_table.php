<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_units', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name'); // e.g., Bureau, UPT, Center
        });
    }

    public function down(): void
    {
        Schema::table('work_units', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
