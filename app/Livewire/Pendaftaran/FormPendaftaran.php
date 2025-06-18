<?php

namespace App\Livewire\Pendaftaran;

use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\ApplicationProgramChoice;
use App\Models\Batch;
use App\Models\Prospective;
use App\Models\User;
use App\Models\HighSchool;
use App\Models\Program;
use App\Models\Religion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class FormPendaftaran extends Component
{
    // Properti untuk data User & Prospective
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $birth_place = '';
    public string $birth_date = '';
    public string $gender = '';
    public string $address = '';
    public string $nisn = '';
    public string $id_number = ''; // NIK

    // Properti untuk data orang tua
    public string $father_name = '';
    public string $mother_name = '';
    public string $father_occupation = '';
    public string $mother_occupation = '';
    public string $parent_phone = ''; 

    // Properti untuk data Wali
    public string $guardian_name = '';       
    public string $guardian_phone = '';      
    public string $guardian_occupation = ''; 

    // Properti untuk relasi
    public ?int $religion_id = null;
    public ?int $high_school_id = null;

    // Properti untuk Pilihan Prodi
    public ?int $program_choice_1 = null;
    public ?int $program_choice_2 = null;

      public $selectedCategory;
    public $selectedBatch;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'address' => 'required|string',
            'nisn' => 'required|string|max:20|unique:prospectives,nisn',
            'id_number' => 'required|string|max:20|unique:prospectives,id_number',
            
            // Aturan untuk data orang tua
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_occupation' => 'required|string|max:255',
            'mother_occupation' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:15',

            // Aturan untuk data wali (semua opsional)
            'guardian_name' => 'nullable|string|max:255',       
            'guardian_phone' => 'nullable|string|max:15',      
            'guardian_occupation' => 'nullable|string|max:255', 
            
            // Aturan untuk relasi
            'religion_id' => 'required|exists:religions,id',
            'high_school_id' => 'required|exists:high_schools,id',
            'program_choice_1' => 'required|exists:programs,id',
            'program_choice_2' => 'nullable|exists:programs,id|different:program_choice_1',

            
        ];
    }

    public function mount($categorySlug = null, $batchId = null)
    {
        if ($categorySlug) {
            // Cari kategori pendaftaran di database berdasarkan slug-nya
            $this->selectedCategory = AdmissionCategory::where('slug', $categorySlug)->first();
        }

        if ($batchId) {
            // Cari gelombang pendaftaran berdasarkan ID-nya
            $this->selectedBatch = Batch::find($batchId);
        }

        // Jika tidak ada parameter batchId di URL, kita set default ke gelombang yang sedang aktif
        if (!$this->selectedBatch) {
            $this->selectedBatch = Batch::where('is_active', true)->first();
        }
    }


    /**
     * Method yang akan dieksekusi saat form disubmit.
     */
    public function save()
    {
        // 1. Jalankan validasi
        $validatedData = $this->validate();

        // 2. Mulai Database Transaction
        DB::transaction(function () use ($validatedData) {
            // 3. Buat User baru (tanpa password) dan beri role 'Camaru'
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => null, // Password akan di-set oleh user nanti
            ]);
            $user->assignRole('Camaru');

            // 4. Buat data Prospective (biodata)
            $prospective = Prospective::create([
                'user_id' => $user->id,
                'id_number' => $validatedData['id_number'],
                'nisn' => $validatedData['nisn'],
                'birth_place' => $validatedData['birth_place'],
                'birth_date' => $validatedData['birth_date'],
                'gender' => $validatedData['gender'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'father_name' => $validatedData['father_name'],
                'mother_name' => $validatedData['mother_name'],
                'father_occupation' => $validatedData['father_occupation'],
                'mother_occupation' => $validatedData['mother_occupation'],
                'parent_phone' => $validatedData['parent_phone'],
                'guardian_name' => $validatedData['guardian_name'],
                'guardian_phone' => $validatedData['guardian_phone'],
                'guardian_occupation' => $validatedData['guardian_occupation'],
                'religion_id' => $validatedData['religion_id'],
                'high_school_id' => $validatedData['high_school_id'],
            ]);

            // 5. Buat data Application (pendaftaran)
            $application = Application::create([
                'prospective_id' => $prospective->id,
                'batch_id' => 1, // Ganti dengan ID gelombang aktif
                'admission_category_id' => 1, // Ganti dengan ID jalur pendaftaran yang dipilih
                'registration_number' => 'PMB' . date('Y') . '-' . str_pad(Application::count() + 1, 5, '0', STR_PAD_LEFT),
                'status' => 'awaiting_payment',
            ]);
            
            // 6. Simpan Pilihan Prodi
            ApplicationProgramChoice::create([
                'application_id' => $application->id,
                'program_id' => $validatedData['program_choice_1'],
                'choice_order' => 1,
            ]);

            if (!empty($validatedData['program_choice_2'])) {
                ApplicationProgramChoice::create([
                    'application_id' => $application->id,
                    'program_id' => $validatedData['program_choice_2'],
                    'choice_order' => 2,
                ]);
            }
        });

        // 7. Redirect ke halaman sukses
        // Kita juga akan kirim email notifikasi di sini nanti
        return redirect()->route('pendaftaran.sukses'); // Asumsi ada route bernama 'pendaftaran.sukses'
    }

    public function render()
    {
        // 1. Ambil semua data dari tabel master
        $religions = Religion::orderBy('name')->get();
        $highSchools = HighSchool::orderBy('name')->get();
        // Kita ambil program studi dan kita kelompokkan berdasarkan fakultasnya
        $programs = Program::with('faculty')->orderBy('faculty_id')->get()->groupBy('faculty.name_id');

        // 2. Kirim data tersebut ke view
        return view('livewire.pendaftaran.form-pendaftaran', [
            'religions' => $religions,
            'highSchools' => $highSchools,
            'programs' => $programs,
        ]);
        return view('livewire.pendaftaran.form-pendaftaran');
    }
}