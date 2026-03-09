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
        // --- KATEGORI PEMASUKAN (INCOME) ---
        $incomes = [
            [
                'name' => 'Pembayaran Mahasiswa',
                'description' => 'Pemasukan otomatis dari pembayaran SPP, PMB, dan tagihan akademik lainnya.'
            ],
            [
                'name' => 'Donasi & Hibah',
                'description' => 'Sumbangan dari alumni, hibah pemerintah, atau pihak ketiga.'
            ],
            [
                'name' => 'Penjualan Aset',
                'description' => 'Pemasukan dari penjualan aset atau inventaris kampus.'
            ],
            [
                'name' => 'Sewaan Gedung/Fasilitas',
                'description' => 'Penyewaan aula, kantin, atau fasilitas olahraga.'
            ],
            [
                'name' => 'Bunga Bank & Investasi',
                'description' => 'Pendapatan dari bagi hasil bank atau instrumen investasi.'
            ],
        ];

        foreach ($incomes as $income) {
            TransactionCategory::firstOrCreate(
                ['name' => $income['name'], 'type' => 'income'],
                ['description' => $income['description']]
            );
        }

        // --- KATEGORI PENGELUARAN (EXPENSE) ---
        $expenses = [
            [
                'name' => 'Gaji & Tunjangan',
                'description' => 'Pembayaran gaji dosen, staf, dan honorarium.'
            ],
            [
                'name' => 'Operasional Kantor',
                'description' => 'Biaya ATK, fotokopi, konsumsi rapat, dll.'
            ],
            [
                'name' => 'Listrik, Air, Internet',
                'description' => 'Tagihan utilitas bulanan.'
            ],
            [
                'name' => 'Pemeliharaan Gedung & Sarpras',
                'description' => 'Renovasi, perbaikan AC, kebersihan lingkungan.'
            ],
            [
                'name' => 'Pemasaran & Promosi',
                'description' => 'Biaya iklan PMB, brosur, dan event promosi.'
            ],
            [
                'name' => 'Penelitian & Pengabdian',
                'description' => 'Dana hibah internal untuk dosen dan mahasiswa.'
            ],
        ];

        foreach ($expenses as $expense) {
            TransactionCategory::firstOrCreate(
                ['name' => $expense['name'], 'type' => 'expense'],
                ['description' => $expense['description']]
            );
        }
    }
}
