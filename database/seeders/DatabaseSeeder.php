<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create(); 
        $this->call([
            ProvincesSeeder::class,
            CitiesSeeder::class,
            DistrictsSeeder::class,
            VillagesSeeder::class,
            HighSchoolMajorSeeder::class,
            AdmissionCategorySeeder::class,
            BatchSeeder::class,
            FacultyAndProgramSeeder::class,
            SettingSeeder::class,
            ReligionSeeder::class,
            HighSchoolSeeder::class,
            DocumentRequirementSeeder::class, 
            AdmissionCategoryDocumentSeeder::class, 
            AdmissionCategoryBatchSeeder::class,            
            RolePermissionSeeder::class,
            WorkUnitSeeder::class,
            FeeComponentSeeder::class,    
            FeeStructureSeeder::class,
            AcademicYearSeeder::class,    
            CurriculumSeeder::class,
            SdmSeeder::class,
            TransactionCategorySeeder::class, // Added Finance Categories
        ]);

        // Panggil seeder pengimpor data dari file Excel/CSV utuk dosen, mahasiswa lama, dan mata kuliah
        $this->call(DataImportSeeder::class);

        // Panggil seeder pengisi kurikulum di PALING AKHIR
        $this->call(CurriculumContentSeeder::class);
    }
}
