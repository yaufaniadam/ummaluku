<?php

use App\Http\Controllers\Modules\PMB\AcceptedStudentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Modules\PMB\PendaftaranController;
// use App\Livewire\Admin\Pendaftaran\Index as PendaftaranIndex;
use App\Livewire\Admin\Pendaftaran\Show as PendaftaranShow;
// use App\Livewire\Pendaftar\Dashboard as PendaftarDashboard;
use App\Http\Controllers\Modules\PMB\PendaftarDashboardController;
use App\Http\Controllers\Modules\PMB\DocumentUploadController;
use App\Models\Batch;
use App\Models\AdmissionCategory;
use App\Http\Controllers\Modules\PMB\AdminSeleksiController;
use App\Http\Controllers\Modules\PMB\AdminPendaftaranController;
use App\Http\Controllers\Modules\PMB\AdmissionCategoryController;

Route::get('/', function () {
    // Ambil semua kategori pendaftaran yang aktif
    $categories = AdmissionCategory::where('is_active', true)->get();

    // Ambil satu gelombang yang sedang aktif saat ini
    $activeBatch = Batch::where('is_active', true)->first();

    // Kirim KEDUA variabel ($categories dan $activeBatch) ke view 'home'
    return view('user.home', [
        'categories' => $categories,
        'activeBatch' => $activeBatch,
    ]);
})->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/jalur/{category:slug}', [PendaftaranController::class, 'showCategoryDetail'])->name('pendaftaran.category.detail');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // admin pendaftaran
    // Route::get('/admin/pendaftaran', PendaftaranIndex::class)->name('admin.pendaftaran.index');
    Route::get('/admin/pendaftaran', [AdminPendaftaranController::class, 'index'])->name('admin.pendaftaran.index');
    // Route::get('/admin/pendaftaran/{application}', [AdminPendaftaranController::class, 'show'])->name('admin.pendaftaran.show');
    Route::get('/admin/pendaftaran/{application}', PendaftaranShow::class)->name('admin.pendaftaran.show');

    
    Route::get('/admin/seleksi', [AdminSeleksiController::class, 'index'])->name('admin.seleksi.index');
    Route::get('/admin/seleksi/data', [AdminSeleksiController::class, 'data'])->name('admin.seleksi.data');
    Route::post('/seleksi/{application}/accept', [AdminSeleksiController::class, 'accept'])->name('admin.seleksi.accept');
    Route::post('/seleksi/{application}/reject', [AdminSeleksiController::class, 'reject'])->name('admin.seleksi.reject');
    Route::get('/admin/diterima', [AcceptedStudentController::class, 'index'])->name('admin.diterima.index');


    Route::resource('/jalur-pendaftaran', AdmissionCategoryController::class, [
    'as' => 'pmb']);
});


Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.form');
Route::get('/pendaftaran/sukses', function () {
    return view('sukses');
})->name('pendaftaran.sukses');

Route::prefix('admin')->middleware(['role:Super Admin|Admin PMB'])->name('admin.')->group(function () {
        
     
       Route::resource('jalur-pendaftaran', AdmissionCategoryController::class, [
    'as' => 'pmb' // <-- KEMBALIKAN PARAMETER INI
]);

    });



Route::middleware('auth')->group(function () {
    Route::get('/camaru/dashboard', [PendaftarDashboardController::class, 'showDashboard'])->name('pendaftar.dashboard');
    Route::post('/camaru/dashboard/upload-document/{application}', [DocumentUploadController::class, 'store'])->name('pendaftar.document.store');
});

require __DIR__ . '/auth.php';
