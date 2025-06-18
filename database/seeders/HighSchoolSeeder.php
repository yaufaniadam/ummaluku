<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HighSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['SMAN 1 AMBON'], ['SMAN 2 AMBON'], ['SMAN 3 AMBON'], ['SMAN 4 AMBON'],
            ['SMAN 5 AMBON'], ['SMAN 6 AMBON'], ['SMAN 7 AMBON'], ['SMAN 8 AMBON'],
            ['SMAN 9 AMBON'], ['SMAN 10 AMBON'], ['SMAN 11 AMBON'], ['SMAN 12 AMBON'],
            ['SMAN 13 AMBON'], ['SMKN 1 AMBON'], ['SMKN 2 AMBON'], ['SMKN 3 AMBON'],
            ['SMKN 4 AMBON'], ['SMKN 5 AMBON'], ['SMKN 6 AMBON'], ['SMKN 7 AMBON'],
            ['SMKN 8 AMBON'],
        ];

        $now = Carbon::now();
        $insert = [];

        foreach ($data as $row) {
            $insert[] = [
                'name' => $row[0],
                'npsn' => null,
                'address' => null,
                // Logika untuk menentukan tipe sekolah secara otomatis
                'type' => Str::startsWith($row[0], 'SMAN') ? 'SMA' : 'SMK',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Memasukkan semua data ke database dalam satu query
        DB::table('high_schools')->insert($insert);
    }
}