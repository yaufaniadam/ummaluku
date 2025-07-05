<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProspectiveFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // <-- Kunci: Otomatis buat user baru
            'birth_place' => fake()->city(),
            'birth_date' => fake()->date(),
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'religion_id' => '1',
            'citizenship' => 'WNI',
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'nisn' => fake()->unique()->numerify('##########'), // 10 digit angka
            'id_number' => fake()->unique()->numerify('################'), // 16 digit angka
            'high_school_id' => fake()->numberBetween(1,1),
            'high_school_major_id' => fake()->numberBetween(1, 5),
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'father_occupation' => fake()->jobTitle(),
            'mother_occupation' => fake()->jobTitle(),
            'parent_phone' => fake()->phoneNumber(),
            'father_income' => fake()->numberBetween(2, 10) * 1000000,
            'mother_income' => fake()->numberBetween(1, 5) * 1000000,
            'mother_income' => fake()->numberBetween(1, 5) * 1000000,
            'province_code' => '81',
            'city_code' => '8101',
            'district_code' => '810101',
            'village_code' => '8101011009',            
        ];
    }
}
