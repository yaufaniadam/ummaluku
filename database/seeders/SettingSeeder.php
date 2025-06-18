<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'university_name',
                'value' => 'Universitas Muhammadiyah Maluku',
                'type' => 'string',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'key' => 'university_short_name',
                'value' => 'UNIMAL',
                'type' => 'string',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'key' => 'university_address',
                'value' => 'Jl. Pendidikan No. 1, Ambon, Maluku',
                'type' => 'text',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'key' => 'university_phone',
                'value' => '(0911) 123-456',
                'type' => 'string',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'key' => 'active_batch_id',
                'value' => '1', // ID gelombang pendaftaran yang sedang aktif
                'type' => 'integer',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}