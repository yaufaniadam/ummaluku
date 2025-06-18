<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pendaftaran\FormPendaftaran; 
use App\Livewire\Admin\Pendaftaran\Index as PendaftaranIndex;
use App\Livewire\Admin\Pendaftaran\Show as PendaftaranShow;
use App\Http\Controllers\Modules\PMB\PendaftaranController;
use App\Models\AdmissionCategory;

Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    // Ambil semua kategori pendaftaran yang aktif
    $categories = AdmissionCategory::where('is_active', true)->get();
    
    // Kirim data kategori ke view 'welcome.blade.php'
    return view('welcome', ['categories' => $categories]);
})->name('home'); // <-- Kita beri nama 'home'

Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.form');


Route::get('/pendaftaran/sukses', function () {
    return view('sukses'); 
})->name('pendaftaran.sukses');

Route::get('/admin/pendaftaran', PendaftaranIndex::class)->name('admin.pendaftaran.index');
Route::get('/admin/pendaftaran/{application}', PendaftaranShow::class)->name('admin.pendaftaran.show');


