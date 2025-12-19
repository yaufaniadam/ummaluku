<?php

namespace App\Livewire\Mahasiswa\Profil;

use App\Models\Prospective;
use App\Models\Religion;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use App\Models\HighSchool;
use App\Models\HighSchoolMajor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class UpdateForm extends Component
{
    public Prospective $prospective;

    // Properti Form (disamakan dengan form pendaftar)
    public $full_name, $id_number, $nisn, $religion_id, $citizenship, $high_school_id, $high_school_major_id;
    public $is_kps_recipient = 0;
    public $address, $province_code, $city_code, $district_code, $village_code;
    public $father_name, $father_occupation, $father_income;
    public $mother_name, $mother_occupation, $mother_income;
    public $parent_phone, $with_guardian = false;
    public $guardian_name, $guardian_occupation, $guardian_income;
    public $birth_place, $birth_date, $gender, $phone;

    // Properti untuk modal sekolah
    public $nama_sekolah;
    public bool $showModal = false;

    // Data untuk dropdown
    public $religions, $highSchoolMajors, $provinces;
    public $cities = [], $districts = [], $villages = [];

    public function mount()
    {
        // Ambil data prospective dari user yang sedang login
        $this->prospective = Auth::user()->prospective;

        // Ambil data master untuk dropdown
        $this->religions = Religion::orderBy('name')->get();
        $this->highSchoolMajors = HighSchoolMajor::orderBy('name')->get();
        $this->provinces = Province::orderBy('name')->get();

        // Isi properti form dengan data yang sudah ada
        $this->fill($this->prospective->toArray());
        $this->full_name = $this->prospective->user->name;

        // Pre-load data wilayah jika sudah ada
        if ($this->province_code) {
            $this->cities = City::where('province_code', $this->province_code)->orderBy('name')->get();
        }
        if ($this->city_code) {
            $this->districts = District::where('city_code', $this->city_code)->orderBy('name')->get();
        }
        if ($this->district_code) {
            $this->villages = Village::where('district_code', $this->district_code)->orderBy('name')->get();
        }

        // Isi nama sekolah jika sudah ada
        if ($this->high_school_id) {
            $sekolah = HighSchool::find($this->high_school_id);
            if ($sekolah) {
                $this->nama_sekolah = $sekolah->name;
            }
        }
    }

    // Listener untuk perubahan wilayah
    public function updatedProvinceCode($value)
    {
        $this->cities = City::where('province_code', $value)->orderBy('name')->get();
        $this->reset(['city_code', 'district_code', 'village_code']);
    }
    public function updatedCityCode($value)
    {
        $this->districts = District::where('city_code', $value)->orderBy('name')->get();
        $this->reset(['district_code', 'village_code']);
    }
    public function updatedDistrictCode($value)
    {
        $this->villages = Village::where('district_code', $value)->orderBy('name')->get();
        $this->reset('village_code');
    }

    protected function rules()
    {
        return [
            // 'full_name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:10,15',
            // 'id_number' => 'required|numeric|digits:16|unique:prospectives,id_number,' . $this->prospective->id,
            //'nisn' => 'required|numeric|digits:10|unique:prospectives,nisn,' . $this->prospective->id,
            'birth_place' => 'required|string',
            'birth_date' => 'required|date',
            // 'gender' => 'required|in:Laki-laki,Perempuan',
            'religion_id' => 'required|exists:religions,id',
            // 'citizenship' => 'required|in:WNI,WNA',
            // 'high_school_id' => 'required|exists:high_schools,id',
            // 'high_school_major_id' => 'required|exists:high_school_majors,id',
            // 'is_kps_recipient' => 'required|boolean',
            // 'address' => 'required|string',
            // 'province_code' => 'required|exists:indonesia_provinces,code',
            // 'city_code' => 'required|exists:indonesia_cities,code',
            // 'district_code' => 'required|exists:indonesia_districts,code',
            // 'village_code' => 'required|exists:indonesia_villages,code',
            // 'father_name' => 'required|string',
            // 'father_occupation' => 'required|string',
            // 'father_income' => 'required|numeric',
            // 'mother_name' => 'required|string',
            // 'mother_occupation' => 'required|string',
            // 'mother_income' => 'required|numeric',
            // 'parent_phone' => 'required|numeric|digits_between:10,15',
            // 'with_guardian' => 'nullable|boolean',
            // 'guardian_name' => 'nullable|string',
            // 'guardian_occupation' => 'nullable|string',
            // 'guardian_income' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.numeric' => 'Nomor telepon harus berupa angka.',
            'phone.digits_between' => 'Nomor telepon minimal 10 digit dan maksimal 15 digit.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
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
            'high_school_major_id.required' => 'Jurusan harus dipilih',
            'parent_phone.required' => 'Nomor telepon wajib diisi.',
            'parent_phone.numeric' => 'Nomor telepon harus berupa angka.',
            'parent_phone.digits_between' => 'Nomor telepon minimal 10 digit dan maksimal 15 digit.',
        ];
    }
    
    public function updateProfile()
    {
        $validatedData = $this->validate();
        
        $this->prospective->update($validatedData);

        // Perbarui juga nama di tabel users agar sinkron
        Auth::user()->update(['name' => $this->full_name]);

        session()->flash('success', 'Profil Anda berhasil diperbarui.');
    }

    public function setSchool($data)
    {
        // Expecting data to contain: npsn, name, address, village, etc.
        // Or if it comes from existing database, it might have id.
        // But our Search API returns NPSN as ID.

        $npsn = $data['npsn'] ?? null;
        if (!$npsn) {
            return;
        }

        $sekolah = HighSchool::updateOrCreate(
            ['npsn' => $npsn],
            [
                'name' => $data['name'],
                'satuanPendidikanId' => $data['satuanPendidikanId'] ?? null,
                'address' => $data['address'] ?? null,
                'village' => $data['village'] ?? null,
                // 'city' => $data['city'] ?? null,
                // 'province' => $data['province'] ?? null,
            ]
        );

        $this->high_school_id = $sekolah->id;
        $this->nama_sekolah = $sekolah->name;
    }

    public function render()
    {
        return view('livewire.mahasiswa.profil.update-form');
    }
}
