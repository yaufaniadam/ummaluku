<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('batches')->insert([
            [
                'name' => 'Gelombang 1',
                'description' => '',
                'year' => '2025',
                'start_date' => '2025-01-01',
                'end_date' => '2025-05-31',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gelombang 2',
                'description' => '',
                'year' => '2025',
                'start_date' => '2025-06-01',
                'end_date' => '2025-08-31',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gelombang 1',
                'description' => 'Khusus PSB',
                'year' => '2025',
                'start_date' => '2025-01-01',
                'end_date' => '2025-05-31',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}