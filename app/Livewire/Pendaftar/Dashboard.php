<?php

namespace App\Livewire\Pendaftar;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\HighSchool;
use App\Models\Religion;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use PhpParser\Node\Expr\Cast\Array_;

class Dashboard extends Component
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
    public $father_name, $mother_name, $father_occupation, $mother_occupation, $parent_phone;
    public $guardian_name, $guardian_phone, $guardian_occupation;

    protected function rules(): array
    {
        return [
            'nisn' => 'required|numeric|digits:10',
            'id_number' => 'required|numeric|digits:16',
            'address' => 'required|string',
            'religion_id' => 'required|exists:religions,id',
            'high_school_id' => 'required|exists:high_schools,id',
            // 'father_name' => 'required|string|max:255',
            // 'mother_name' => 'required|string|max:255',
            // 'father_occupation' => 'required|string|max:255',
            // 'mother_occupation' => 'required|string|max:255',
            // 'parent_phone' => 'required|string|max:15',
            // 'guardian_name' => 'nullable|string|max:255',
            // 'guardian_phone' => 'nullable|string|max:15',
            // 'guardian_occupation' => 'nullable|string|max:255',
            'province_code' => 'required|exists:indonesia_provinces,code',
            'city_code' => 'required|exists:indonesia_cities,code',
            // 'district_code' => 'required|exists:indonesia_districts,code',
            // 'village_code' => 'required|exists:indonesia_villages,code',
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

        // Beri notifikasi sukses menggunakan SweetAlert
        $this->dispatch('swal-success', [
            'message' => 'Biodata Anda berhasil diperbarui!',
        ]);
    }

    public function render()
    {
        $religions = Religion::orderBy('id')->get();
        $highSchools = HighSchool::orderBy('name')->get();

        $this->application->refresh();

        // Kirim semua data (termasuk data untuk dropdown) ke view
        return view('livewire.pendaftar.dashboard', [
            'religions' => $religions,
            'highSchools' => $highSchools,
        ]);
    }
}
