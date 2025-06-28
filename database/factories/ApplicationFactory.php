<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationProgramChoice;
use App\Models\Program;
use App\Models\Prospective;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prospective_id' => Prospective::factory(),
            'batch_id' => 1,
            'admission_category_id' => fake()->numberBetween(1, 4),
            'registration_number' => 'PMB' . date('Y') . '-' . fake()->unique()->numerify('#####'),
            'status' => 'awaiting_verification',
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Application $application) {
            // Setelah satu pendaftaran dibuat, jalankan kode ini.

            // 1. Ambil dua program studi yang berbeda secara acak
            $programs = Program::inRandomOrder()->take(2)->pluck('id');

            // 2. Buat pilihan prodi pertama
            ApplicationProgramChoice::factory()->create([
                'application_id' => $application->id,
                'program_id' => $programs[0],
                'choice_order' => 1,
            ]);

            // 3. Buat pilihan prodi kedua
            ApplicationProgramChoice::factory()->create([
                'application_id' => $application->id,
                'program_id' => $programs[1],
                'choice_order' => 2,
            ]);
        });
    }
}
