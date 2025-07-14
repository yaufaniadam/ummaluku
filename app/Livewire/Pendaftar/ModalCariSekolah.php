<?php

namespace App\Livewire\Pendaftar;

use App\Models\HighSchool;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ModalCariSekolah extends Component
{
    // Properti untuk menampung data dari API
    public $provinsis = [];
    public $kabupatens = [];
    public $kecamatans = [];
    public $sekolahs = [];

    // Properti untuk menampung pilihan pengguna
    public $selectedProvinsi = null;
    public $selectedKabupaten = null;
    public $selectedKecamatan = null;

    // Method yang pertama kali dijalankan saat komponen dimuat
    public function mount()
    {
        // Ambil data provinsi saat komponen pertama kali dimuat
        $response = Http::get('https://api.data.belajar.id/data-portal-backend/v1/master-data/satuan-pendidikan/statistics/360/descendants?level=1');
        $this->provinsis = $response->json('data') ?? [];

        // dd($response);
    }

    // Lifecycle hook: dijalankan setiap kali $selectedProvinsi berubah
    public function updatedSelectedProvinsi($kode_provinsi)
    {

        // dd($kode_provinsi);
        // Ambil data kabupaten berdasarkan provinsi yang dipilih
        $response = Http::get("https://api.data.belajar.id/data-portal-backend/v1/master-data/satuan-pendidikan/statistics/" . $kode_provinsi . "/descendants?jalurPendidikan=formal&bentukPendidikan=dikmen&sortBy=bentuk_pendidikan&sortDir=asc
        ");
        $this->kabupatens = $response->json('data') ?? [];

        // Reset pilihan dan data di bawahnya
        $this->reset(['selectedKabupaten']);
        // $this->reset(['selectedKabupaten', 'selectedKecamatan', 'kecamatans', 'sekolahs']);
    }

    // Lifecycle hook: dijalankan setiap kali $selectedKabupaten berubah
    public function updatedSelectedKabupaten($kode_kabupaten)
    {
        // Ambil data kecamatan berdasarkan kabupaten yang dipilih
        $response = Http::get("https://api.data.belajar.id/data-portal-backend/v1/master-data/satuan-pendidikan/statistics/" . $kode_kabupaten . "/descendants?bentukPendidikan=dikmen&sortBy=bentuk_pendidikan&sortDir=asc");
        $this->kecamatans = $response->json('data') ?? [];

        // Reset pilihan dan data di bawahnya
        $this->reset(['selectedKecamatan', 'sekolahs']);
    }

    // Lifecycle hook: dijalankan setiap kali $selectedKecamatan berubah

    public function updatedSelectedKecamatan($kode_kecamatan)
    {
        if (!empty($kode_kecamatan)) {
            // Gunakan API search sekolah yang sebelumnya
            // $response = Http::get("https://api.data.belajar.id/data-portal-backend/v1/master-data/ptk/search?kodeKecamatan=" . $kode_kecamatan . "&bentukPendidikan=dikmen&sortBy=bentuk_pendidikan&sortDir=asc&limit=1000&offset=0");

            $response = Http::get("https://api.data.belajar.id/data-portal-backend/v2/master-data/peserta-didik/search?kodeKecamatan=" . $kode_kecamatan . "&bentukPendidikan=dikmen&sortBy=bentuk_pendidikan&sortDir=ASC&limit=20&offset=0");
            $this->sekolahs = $response->json('data') ?? [];
        }
    }

    /**
     * Method ini dipanggil ketika user menekan tombol "Pilih"
     * di salah satu sekolah pada hasil pencarian.
     */
    public function pilihSekolah($sekolahId, $npsn, $namaSekolah, $alamatJalan, $namaDesa)
    {

        $sekolah = HighSchool::updateOrCreate(
            ['npsn' => $npsn],
            [
                'name' => $namaSekolah,
                'satuanPendidikanId' => $sekolahId,
                'address' => $alamatJalan,
                'village' => $namaDesa,
            ]
        );

        $lastId = $sekolah->id;

        // Mengirim event ke komponen lain (dalam kasus ini, ke FormSiswa)
        // bahwa sebuah sekolah telah dipilih.
        // Kita mengirim ID dan namanya.
        $this->dispatch('sekolahDipilih', id: $lastId, nama: $namaSekolah);
    }

    public function render()
    {
        return view('livewire.pendaftar.modal-cari-sekolah');
    }
}
