<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Income Categories
        TransactionCategory::firstOrCreate(
            ['name' => 'Pembayaran Mahasiswa', 'type' => 'income'],
            ['description' => 'Pemasukan otomatis dari pembayaran SPP/PMB mahasiswa']
        );

        TransactionCategory::firstOrCreate(
            ['name' => 'Donasi', 'type' => 'income'],
            ['description' => 'Sumbangan atau hibah']
        );

        // Default Expense Categories
        TransactionCategory::firstOrCreate(
            ['name' => 'Operasional', 'type' => 'expense'],
            ['description' => 'Biaya operasional sehari-hari']
        );

        TransactionCategory::firstOrCreate(
            ['name' => 'Gaji Pegawai', 'type' => 'expense'],
            ['description' => 'Pembayaran gaji dosen dan staf']
        );
    }
}
