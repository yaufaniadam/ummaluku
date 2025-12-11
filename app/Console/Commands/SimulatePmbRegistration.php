<?php

namespace App\Console\Commands;

use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ApplicationProgramChoice;
use App\Models\Batch;
use App\Models\Program;
use App\Models\Prospective;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class SimulatePmbRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pmb:simulate-registration {count=1 : Number of students to simulate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate new student registration until document upload';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        $faker = Faker::create('id_ID');

        // 1. Get Active Batch
        $batch = Batch::where('is_active', true)->first();
        if (!$batch) {
            $this->error('No active batch found!');
            return 1;
        }
        $this->info("Using Batch: {$batch->name} ({$batch->year})");

        // 2. Loop for requested count
        for ($i = 0; $i < $count; $i++) {
            DB::transaction(function () use ($faker, $batch) {
                // Get a category linked to this batch
                $category = $batch->admissionCategories()->inRandomOrder()->first();
                if (!$category) {
                    // Fallback if no specific link, try getting any active category
                    $category = AdmissionCategory::where('is_active', true)->inRandomOrder()->first();
                }

                if (!$category) {
                     throw new \Exception("No admission category found.");
                }

                // Get Programs
                $programs = Program::inRandomOrder()->limit(2)->get();
                if ($programs->isEmpty()) {
                    throw new \Exception("No programs found.");
                }

                // 3. Create User
                $email = $faker->unique()->email;
                $user = User::create([
                    'name' => $faker->name,
                    'email' => $email,
                    'password' => Hash::make('pass123'),
                ]);
                $user->assignRole('Camaru');

                // 4. Create Prospective
                $prospective = Prospective::create([
                    'user_id' => $user->id,
                    'registration_source' => 'simulation',
                    'birth_place' => $faker->city,
                    'birth_date' => $faker->date('Y-m-d', '-18 years'),
                    'gender' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                    'phone' => $faker->numerify('08##########'),
                    'parent_phone' => $faker->numerify('08##########'),
                ]);

                // 5. Create Application
                // Determine status. If we want to simulate "until upload document",
                // we usually need them to be in a state where they CAN upload documents.
                // Assuming 'lengkapi_data' is the state for that.
                $application = Application::create([
                    'prospective_id' => $prospective->id,
                    'batch_id' => $batch->id,
                    'admission_category_id' => $category->id,
                    'registration_number' => 'TEMP-' . uniqid(),
                    'status' => 'lengkapi_data',
                ]);

                // Update Registration Number
                $regNum = 'PMB' . date('Y') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
                $application->registration_number = $regNum;
                $application->save();

                // 6. Program Choices
                foreach ($programs as $index => $program) {
                    ApplicationProgramChoice::create([
                        'application_id' => $application->id,
                        'program_id' => $program->id,
                        'choice_order' => $index + 1,
                    ]);
                }

                // 7. Simulate Document Upload
                $requirements = $category->documentRequirements;

                // If requirements are empty, maybe we need to check the logic in DocumentRequirement/AdmissionCategory
                // But for now we iterate what we found.
                foreach ($requirements as $req) {
                    ApplicationDocument::create([
                        'application_id' => $application->id,
                        'document_requirement_id' => $req->id,
                        'file_path' => 'public/dummy_document.pdf', // Points to the dummy file we created
                        'status' => 'pending', // Pending verification
                        'notes' => 'Simulated upload',
                    ]);
                }

                $this->info("Created Student: {$user->name} | Email: {$user->email} | Pass: pass123 | Reg: {$regNum}");
            });
        }

        $this->info("Simulation completed for {$count} student(s).");
        return 0;
    }
}
