<?php

namespace Database\Seeders;

use App\Models\FeeComponent;
use Illuminate\Database\Seeder;

class FeeComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FeeComponent::create([
            'name' => 'SPP Tetap',
            'type' => 'fixed',
        ]);

        FeeComponent::create([
            'name' => 'SPP Variabel',
            'type' => 'per_sks',
        ]);

        FeeComponent::create([
            'name' => 'Praktikum',
            'type' => 'per_course',
        ]);
    }
}