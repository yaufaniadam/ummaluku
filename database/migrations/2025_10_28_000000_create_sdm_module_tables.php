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
        // 1. Employee Ranks (Golongan/Pangkat)
        Schema::create('employee_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('grade')->unique(); // e.g., 'III/a', 'IV/b'
            $table->string('name'); // e.g., 'Penata Muda'
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Structural Positions (Jabatan Struktural)
        Schema::create('structural_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Rektor', 'Dekan', 'Kepala Biro'
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Functional Positions (Jabatan Fungsional)
        Schema::create('functional_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Lektor', 'Asisten Ahli', 'Pranata Komputer'
            $table->string('code')->nullable()->unique();
            $table->enum('type', ['academic', 'non-academic'])->default('academic');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Employment Statuses (Status Kepegawaian)
        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Tetap', 'Kontrak', 'Honorer', 'DLB'
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. Document Types (Jenis Dokumen)
        Schema::create('employee_document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Ijazah S1', 'SK Pengangkatan', 'Sertifikat Dosen'
            $table->boolean('is_mandatory')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // --- Polymorphic History Tables ---

        // 6. Structural History
        Schema::create('employee_structural_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('employee'); // employee_type, employee_id
            $table->foreignId('structural_position_id')->constrained()->onDelete('cascade');
            // Context: Where do they hold this position? (e.g., specific Faculty or Bureau)
            $table->foreignId('work_unit_id')->nullable()->constrained()->onDelete('set null');

            $table->string('sk_number')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Functional History
        Schema::create('employee_functional_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('employee');
            $table->foreignId('functional_position_id')->constrained()->onDelete('cascade');

            $table->string('sk_number')->nullable();
            $table->date('tmt')->comment('Terhitung Mulai Tanggal');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Rank History (Riwayat Pangkat/Golongan)
        Schema::create('employee_rank_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('employee');
            $table->foreignId('employee_rank_id')->constrained()->onDelete('cascade');

            $table->string('sk_number')->nullable();
            $table->date('tmt')->comment('Terhitung Mulai Tanggal');
            $table->integer('years_of_service')->nullable()->comment('Masa Kerja Golongan (Tahun)');
            $table->integer('months_of_service')->nullable()->comment('Masa Kerja Golongan (Bulan)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 9. Employee Documents
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('employee');
            $table->foreignId('employee_document_type_id')->constrained('employee_document_types')->onDelete('cascade');

            $table->string('file_path');
            $table->string('file_name')->nullable(); // Original filename
            $table->text('description')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 10. Add employment_status_id to lecturers and staffs
        Schema::table('lecturers', function (Blueprint $table) {
            $table->foreignId('employment_status_id')->nullable()->constrained('employment_statuses')->onDelete('set null');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->foreignId('employment_status_id')->nullable()->constrained('employment_statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropForeign(['employment_status_id']);
            $table->dropColumn('employment_status_id');
        });

        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropForeign(['employment_status_id']);
            $table->dropColumn('employment_status_id');
        });

        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_rank_histories');
        Schema::dropIfExists('employee_functional_histories');
        Schema::dropIfExists('employee_structural_histories');
        Schema::dropIfExists('employee_document_types');
        Schema::dropIfExists('employment_statuses');
        Schema::dropIfExists('functional_positions');
        Schema::dropIfExists('structural_positions');
        Schema::dropIfExists('employee_ranks');
    }
};
