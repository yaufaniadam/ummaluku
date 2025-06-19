<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pendaftaran\FormPendaftaran; 
use App\Livewire\Admin\Pendaftaran\Index as PendaftaranIndex;
use App\Livewire\Admin\Pendaftaran\Show as PendaftaranShow;
use App\Livewire\Pendaftar\Dashboard as PendaftarDashboard;
use App\Http\Controllers\Modules\PMB\PendaftaranController;
use App\Models\AdmissionCategory;

Auth::routes();
//sebelum pindah ke breeze

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    // Ambil kategori yang aktif DAN memiliki setidaknya satu gelombang aktif yang terhubung
    $categories = AdmissionCategory::where('is_active', true)
        ->whereHas('batches', function ($query) {
            $query->where('is_active', true);
        })
        ->with(['batches' => function ($query) {
            // Saat mengambil data kategori, sertakan HANYA gelombang yang aktif
            $query->where('is_active', true);
        }])
        ->get();

    return view('welcome', ['categories' => $categories]);
})->name('home');

Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.form');
    

Route::get('/pendaftaran/sukses', function () {
    return view('sukses'); 
})->name('pendaftaran.sukses');

Route::get('/admin/pendaftaran', PendaftaranIndex::class)->name('admin.pendaftaran.index');
Route::get('/admin/pendaftaran/{application}', PendaftaranShow::class)->name('admin.pendaftaran.show');

Route::get('/camaru/dashboard', PendaftarDashboard::class)
    ->middleware('auth') // <-- Hanya untuk yang sudah login
    ->name('pendaftar.dashboard');


