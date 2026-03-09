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
use App\Models\Religion;
use App\Models\HighSchool;
use App\Models\HighSchoolMajor;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
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
    protected $description = 'Simulate new student registration until document upload is complete';

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

                // 4. Fetch Helper Data (with fallbacks/checks)
                $religion = Religion::inRandomOrder()->first();
                $highSchool = HighSchool::inRandomOrder()->first();
                $major = HighSchoolMajor::inRandomOrder()->first();

                // Location Chain
                $province = Province::inRandomOrder()->first();
                $city = $province ? City::where('province_code', $province->code)->inRandomOrder()->first() : null;
                $district = $city ? District::where('city_code', $city->code)->inRandomOrder()->first() : null;
                $village = $district ? Village::where('district_code', $district->code)->inRandomOrder()->first() : null;

                // 5. Create Prospective (Complete Profile)
                $prospective = Prospective::create([
                    'user_id' => $user->id,
                    'registration_source' => 'simulation',
                    'birth_place' => $faker->city,
                    'birth_date' => $faker->date('Y-m-d', '-18 years'),
                    'gender' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                    'phone' => $faker->numerify('08##########'),
                    'parent_phone' => $faker->numerify('08##########'),

                    // Profile Fields
                    'nisn' => $faker->unique()->numerify('##########'), // 10 digits
                    'id_number' => $faker->unique()->numerify('################'), // 16 digits (NIK)
                    'address' => $faker->address,
                    'religion_id' => $religion ? $religion->id : 1, // Fallback ID 1 if empty
                    'high_school_id' => $highSchool ? $highSchool->id : 1,
                    'high_school_major_id' => $major ? $major->id : 1,

                    'province_code' => $province ? $province->code : null,
                    'city_code' => $city ? $city->code : null,
                    'district_code' => $district ? $district->code : null,
                    'village_code' => $village ? $village->code : null,
                    'postal_code' => $faker->postcode,

                    'citizenship' => 'WNI',
                    'is_kps_recipient' => false,

                    // Parents
                    'father_name' => $faker->name('male'),
                    'father_occupation' => 'Wiraswasta',
                    'father_income' => 5000000,
                    'father_nik' => $faker->numerify('################'),

                    'mother_name' => $faker->name('female'),
                    'mother_occupation' => 'Ibu Rumah Tangga',
                    'mother_income' => 0,
                    'mother_nik' => $faker->numerify('################'),
                ]);

                // 6. Create Application
                $application = Application::create([
                    'prospective_id' => $prospective->id,
                    'batch_id' => $batch->id,
                    'admission_category_id' => $category->id,
                    'registration_number' => 'TEMP-' . uniqid(),
                    // Initially 'lengkapi_data', will update to 'proses_verifikasi'
                    'status' => 'lengkapi_data',
                ]);

                // Update Registration Number
                $regNum = 'PMB' . date('Y') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);
                $application->registration_number = $regNum;
                $application->save();

                // 7. Program Choices
                foreach ($programs as $index => $program) {
                    ApplicationProgramChoice::create([
                        'application_id' => $application->id,
                        'program_id' => $program->id,
                        'choice_order' => $index + 1,
                    ]);
                }

                // 8. Simulate Document Upload
                $requirements = $category->documentRequirements;

                if ($requirements->count() > 0) {
                    foreach ($requirements as $req) {
                        ApplicationDocument::create([
                            'application_id' => $application->id,
                            'document_requirement_id' => $req->id,
                            'file_path' => 'dummy_document.pdf', // Points to dummy file in storage/app/documents/... usually?
                            // Note: DocumentUploadController stores in 'documents/'.$appId.
                            // We will use a shared dummy path or copy it?
                            // For simulation, pointing to a static file in public storage is likely fine if the UI just links to it.
                            // But `view_file` might expect it in a specific place.
                            // Let's stick to a simple string for now.
                            'file_path' => 'dummy_document.pdf',
                            'status' => 'pending',
                            'notes' => 'Simulated upload',
                        ]);
                    }

                    // Update status to 'proses_verifikasi' as all docs are "uploaded"
                    $application->update(['status' => 'proses_verifikasi']);
                } else {
                    $this->warn("Category {$category->name} has no document requirements. Status remains 'lengkapi_data' or 'upload_dokumen'.");
                    // If no docs required, maybe we should set it to verify anyway?
                    // Safe bet: set to 'upload_dokumen' so they can see emptiness, or 'proses_verifikasi' if logic allows.
                    // But without docs, verification usually fails.
                    // Let's assume we want to show "Profile Filled".
                    $application->update(['status' => 'upload_dokumen']);
                }

                $this->info("Created Student: {$user->name} | Email: {$user->email} | Pass: pass123 | Reg: {$regNum} | Status: {$application->status}");
            });
        }

        $this->info("Simulation completed for {$count} student(s).");
        return 0;
    }
}
