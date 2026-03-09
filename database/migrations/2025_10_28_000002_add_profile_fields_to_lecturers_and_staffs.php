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
        Schema::table('lecturers', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('status');
            $table->text('address')->nullable()->after('phone');
            $table->string('bank_name')->nullable()->after('address');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('birth_place')->nullable()->after('account_number');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->enum('gender', ['L', 'P'])->nullable()->after('birth_date');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'bank_name', 'account_number', 'birth_place', 'birth_date', 'gender']);
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'birth_place', 'birth_date']);
        });
    }
};
