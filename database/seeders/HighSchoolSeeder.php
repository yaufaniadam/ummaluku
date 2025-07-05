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
            ['SMAN 1 AMBON'], 
        ];

        $now = Carbon::now();
        $insert = [];

        foreach ($data as $row) {
            $insert[] = [
                'name' => $row[0],
                'npsn' => '60102008',
                'satuanPendidikanId' => '4c8c345a-8d71-4e48-b706-b9bab6fdb2aa',
                'address' => 'JL.RAYA PATTIMURA NO. 28',
                'village' => 'URITETU',
                'type' => 'SMA SEDERAJAT',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Memasukkan semua data ke database dalam satu query
        DB::table('high_schools')->insert($insert);
    }
}