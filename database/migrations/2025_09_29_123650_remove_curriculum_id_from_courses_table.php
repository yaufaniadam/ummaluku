<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/..._remove_curriculum_id_from_courses_table.php

public function up(): void
{
    Schema::table('courses', function (Blueprint $table) {
        // Hapus foreign key constraint terlebih dahulu
        $table->dropForeign(['curriculum_id']);
        // Kemudian hapus kolomnya
        $table->dropColumn('curriculum_id');
    });
}

public function down(): void
{
    Schema::table('courses', function (Blueprint $table) {
        // Jika kita rollback, buat kembali kolomnya
        $table->foreignId('curriculum_id')->nullable()->constrained()->onDelete('cascade');
    });
}
};
