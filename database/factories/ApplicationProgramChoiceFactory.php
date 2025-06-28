<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationProgramChoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            // application_id akan kita isi secara otomatis dari ApplicationFactory
            'program_id' => Program::inRandomOrder()->first()->id, // Pilih prodi secara acak
            'choice_order' => 1, // Default, bisa diubah nanti
            'is_accepted' => null,
        ];
    }
}