<?php

namespace App\Livewire\Pendaftar;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\HighSchool;
use App\Models\HighSchoolMajor;
use App\Models\Religion;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use PhpParser\Node\Expr\Cast\Array_;

class Biodata extends Component
{
    public Application $application;
    public $requiredDocuments;

    public $province_code;
    public $city_code;
    public $district_code;
    public $village_code;

    public $provinces;
    public $cities = [];
    public $districts = [];
    public $villages = [];

    // Properti BARU untuk menampung data biodata yang akan diisi
    public $nisn, $id_number, $address, $religion_id, $high_school_id;
    public $father_name, $mother_name, $father_occupation, $mother_occupation, $father_income, $mother_income, $parent_phone;
    public $guardian_name, $guardian_phone, $guardian_occupation, $guardian_income, $high_school_major_id;
    // public $npwp;
    public $is_kps_recipient = false; // Beri nilai default false
    public $with_guardian = false;
    public $citizenship;

    public bool $isBiodataComplete = false;

    public array $workflowSteps = [];

    protected function rules(): array
    {
        $prospectiveId = $this->application->prospective->id;

        return [
            'nisn'      => 'required|numeric|digits:10|unique:prospectives,nisn,' . $prospectiveId,
            'id_number' => 'required|numeric|digits:16|unique:prospectives,id_number,' . $prospectiveId,
            'address' => 'required|string',
            'religion_id' => 'required|exists:religions,id',
            'high_school_id' => 'required|exists:high_schools,id',
            'father_name' => 'required|string|max:255',
            'father_occupation' => 'required|string|max:255',
            'father_income' => 'required|numeric',
            'mother_income' => 'required|numeric',
            'mother_name' => 'required|string|max:255',
            'mother_occupation' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:15',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_income' => 'nullable|numeric',
            'province_code' => 'required|exists:indonesia_provinces,code',
            'city_code' => 'required|exists:indonesia_cities,code',
            'district_code' => 'required|exists:indonesia_districts,code',
            'village_code' => 'required|exists:indonesia_villages,code',
            // 'npwp' => 'nullable|string|max:20',
            'is_kps_recipient' => 'required|boolean',
            'with_guardian' => 'nullable|boolean',
            'citizenship' => 'required|string|in:WNI,WNA',
            'high_school_major_id' => 'required',
            
        ];
    }
    public function messages(): array
    {
        return [
            'nisn.required' => 'NISN wajib diisi',
            'nisn.numeric' => 'NISN harus berupa angka.',
            'nisn.digits' => 'NISN harus terdiri dari tepat 10 digit.',
            'nisn.unique' => 'NISN ini sudah terdaftar.',
            'id_number.required' => 'NIK wajib diisi',
            'id_number.numeric' => 'NIK harus berupa angka.',
            'id_number.digits' => 'NIK harus terdiri dari tepat 16 digit.',
            'id_number.unique' => 'NIK ini sudah terdaftar.',
            'address.required' => 'Alamat wajib diisi',
            'religion_id.required' => 'Agama wajib diisi',
            'high_school_id.required' => 'Asal Sekolah wajib diisi',
            'province_code.required' => 'Propinsi wajib diisi',
            'city_code.required' => 'Kabupaten/Kota wajib diisi',
            'district_code.required' => 'Kecamatan wajib diisi',
            'village_code.required' => 'Desa/Kelurahan wajib diisi',
            'father_name.required' => 'Nama Ayah wajib diisi',
            'father_occupation.required' => 'Pekerjaan Ayah wajib diisi',
            'father_income.required' => 'Penghasilan Ayah wajib diisi',
            'father_income.numeric' => 'Penghasilan Ayah harus berupa angka',
            'mother_name.required' => 'Nama Ibu wajib diisi',
            'mother_occupation.required' => 'Pekerjaan Ibu wajib diisi',
            'mother_income.required' => 'Penghasilan Ibu wajib diisi',
            'mother_income.numeric' => 'Penghasilan Ibu harus berupa angka',
            'guardian_income.numeric' => 'Penghasilan Wali harus berupa angka',
            'is_kps_recipient.required' => 'Opsi KPS harus dipilih',
            'citizenship.required' => 'Kewarganegaraan harus dipilih',
        ];
    }
    public function updated($propertyName)
    {
        // Jalankan validasi hanya untuk properti yang baru saja diubah.
        $this->validateOnly($propertyName);
    }
    public function mount()
    {
        $user = Auth::user();
        $this->application = Application::whereHas('prospective', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with([
            'prospective',
            'admissionCategory.documentRequirements',
            'documents',
            'batch',
        ])->firstOrFail();

        // // Isi semua properti form dengan data yang sudah ada di database
        $this->fill($this->application->prospective->toArray());

        $this->provinces = Province::all();

        // Jika sudah ada data wilayah tersimpan, isi dropdown anakannya
        if ($this->province_code) {
            $this->cities = City::where('province_code', $this->province_code)->get();
        }
        if ($this->city_code) {
            $this->districts = District::where('city_code', $this->city_code)->get();
        }
        if ($this->district_code) {
            $this->villages = Village::where('district_code', $this->district_code)->get();
        }

        // Isi properti form dengan data yang sudah ada di database
        $this->fill($this->application->prospective->toArray());

        $this->requiredDocuments = $this->application->admissionCategory->documentRequirements;

    }

    public function updatedProvinceCode($value)
    {
        $this->cities = City::where('province_code', $value)->get();
        $this->reset(['city_code', 'district_code', 'village_code']);
    }

    public function updatedCityCode($value)
    {
        $this->districts = District::where('city_code', $value)->get();
        $this->reset(['district_code', 'village_code']);
    }

    public function updatedDistrictCode($value)
    {
        $this->villages = Village::where('district_code', $value)->get();
        $this->reset('village_code');
    }

    
    /**
     * Method BARU khusus untuk menyimpan biodata
     */
    public function saveBiodata()
    {
        // Validasi data biodata
        $validatedData = $this->validate();

        // Update data di tabel prospectives
        $this->application->prospective->update($validatedData);

        // 3. Update status langsung di tabel applications
        $this->application->update([
            'status' => 'upload_dokumen'
        ]);

        // Beri notifikasi sukses menggunakan SweetAlert
        $this->dispatch('swal-success', [
            'message' => 'Biodata Anda berhasil diperbarui!',
        ]);
    }

    public function render()
    {
        $religions = Religion::orderBy('id')->get();
        $highSchools = HighSchool::orderBy('name')->get();
        $highSchoolMajor = highSchoolMajor::orderBy('name')->get();


        $this->application->refresh();

        // Kirim semua data (termasuk data untuk dropdown) ke view
        return view('livewire.pendaftar.biodata', [
            'religions' => $religions,
            'highSchools' => $highSchools,
            'highSchoolMajor' => $highSchoolMajor,
        ]);
    }
}
