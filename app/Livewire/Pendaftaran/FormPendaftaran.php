<?php

namespace App\Livewire\Pendaftaran;

use App\Models\AdmissionCategory;
use App\Models\Application;
use App\Models\ApplicationProgramChoice;
use App\Models\Batch;
use App\Models\Prospective;
use App\Models\User;
use App\Models\Program;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PendaftaranBerhasil;
use App\Notifications\PendaftaranBaru;
use Illuminate\Support\Facades\Notification;


class FormPendaftaran extends Component
{
    // Properti untuk data User & Prospective
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $birth_date = '';
    public string $gender = '';
    public string $parent_phone = '';
    public ?int $program_choice_1 = null;
    public ?int $program_choice_2 = null;
    public string $birth_place = '';

    public $selectedCategory;
    public $selectedBatch;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|digits_between:10,15',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'parent_phone' => 'required|string|regex:/^[0-9]+$/|between:10,15',
            'program_choice_1' => 'required|exists:programs,id',
            'program_choice_2' => 'nullable|exists:programs,id|different:program_choice_1',
            'birth_place' => 'required|string|max:255',           

        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Mohon isi nama lengkap Anda sesuai ijazah.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email ini sudah pernah digunakan untuk mendaftar.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Nomor telepon harus berupa angka.',
            'phone.between' => 'Nomor telepon minimal 10 digit dan maksimal 15 digit.',
            'parent_phone.required' => 'Nomor telepon Orang Tua / Wali wajib diisi.',
            'parent_phone.regex' => 'Nomor telepon harus berupa angka.',
            'parent_phone.between' => 'Nomor telepon minimal 10 digit dan maksimal 15 digit.',
            'program_choice_1.required' => 'Anda harus memilih minimal satu Program Studi.',
            'program_choice_2.different' => 'Anda harus memilih program studi yang berbeda.',
        ];
    }

    public function updated($propertyName)
    {
        // Jalankan validasi hanya untuk properti yang baru saja diubah
        // untuk efisiensi.
        $this->validateOnly($propertyName);
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

         $generatedPassword = Str::random(10);
    
    // Variabel untuk menampung objek aplikasi
    $applicationObject = null;

        // 2. Hasilkan password acak yang aman
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // Acak kumpulan karakter dan ambil 10 karakter pertama
        $generatedPassword = substr(str_shuffle($characters), 0, 4);

        // 2. Mulai Database Transaction
        $user = DB::transaction(function () use ($validatedData, $generatedPassword) {
            // 3. Buat User baru (tanpa password) dan beri role 'Camaru'
            $newUser = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($generatedPassword), // Password di-set oleh sistem
            ]);

            $newUser->assignRole('Camaru');

            // 4. Buat data Prospective (biodata)
            $prospective = Prospective::create([
                'user_id' => $newUser->id,
                'registration_source' => 'pmb_online', // Set default sumber pendaftaran
                'birth_place' => $validatedData['birth_place'],
                'birth_date' => $validatedData['birth_date'],
                'gender' => $validatedData['gender'],
                'phone' => $validatedData['phone'],
                'parent_phone' => $validatedData['parent_phone'],
            ]);           

            // 3. Buat data Aplikasi dengan nomor registrasi KOSONG terlebih dahulu
            $application = Application::create([
                'prospective_id' => $prospective->id,
                'batch_id' => $this->selectedBatch->id,
                'admission_category_id' => $this->selectedCategory->id,
                'registration_number' => 'TEMP-' . uniqid(), 
                'status' => config('settings.payment_flow_enabled', false) 
                    ? 'lakukan_pembayaran'
                    : 'lengkapi_data',
            ]);

            // 4. Setelah dibuat, kita mendapatkan ID-nya. Gunakan ID ini untuk membuat nomor registrasi
            $registrationNumber = 'PMB' . date('Y') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);

            // 5. Update kembali aplikasi dengan nomor registrasi yang sudah pasti unik
            $application->registration_number = $registrationNumber;
            $application->save();

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

            $newUser->notify(new PendaftaranBerhasil($newUser, $generatedPassword, $application));

            $admins = User::role(['Staf Admisi'])->get();

            Notification::send($admins, new PendaftaranBaru($newUser, $application));

            return $newUser;
        });



        // 5. Redirect ke halaman sukses dengan membawa data login
        return redirect()->route('pendaftaran.sukses')->with('registration_data', [
            'email' => $user->email,
            'password' => $generatedPassword, // Kirim password mentah (belum di-hash)
            'category_name' => $this->selectedCategory->name,
            'batch_name' => $this->selectedBatch->name,
            'batch_year' => $this->selectedBatch->year,
        ]);
    }



    public function render()
    {
        // 1. Ambil semua data dari tabel master
        // $religions = Religion::orderBy('name')->get();
        // $highSchools = HighSchool::orderBy('name')->get();
        // Kita ambil program studi dan kita kelompokkan berdasarkan fakultasnya
        $programs = Program::with('faculty')->orderBy('faculty_id')->get()->groupBy('faculty.name_id');

        // 2. Kirim data tersebut ke view
        return view('livewire.pendaftaran.form-pendaftaran', [
            // 'religions' => $religions,
            // 'highSchools' => $highSchools,
            'programs' => $programs,
        ]);
        return view('livewire.pendaftaran.form-pendaftaran');
    }
}
