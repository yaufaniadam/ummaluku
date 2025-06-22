<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HighSchoolMajorSeeder extends Seeder
{
    public function run(): void
    {
        $majors = ['IPA', 'IPS', 'Bahasa', 'Agama', 'Kejuruan', 'Lainnya'];
        foreach ($majors as $major) {
            DB::table('high_school_majors')->insert([
                'name' => $major,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}