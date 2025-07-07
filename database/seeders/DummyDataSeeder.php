<?php
namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Perintah untuk membuat 50 data pendaftaran lengkap secara acak
        Application::factory(5)->create();
    }
}