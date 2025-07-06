<?php

use App\Http\Controllers\Modules\PMB\AcceptedStudentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Modules\PMB\PendaftaranController;
// use App\Livewire\Admin\Pendaftaran\Index as PendaftaranIndex;
use App\Livewire\Admin\Pendaftaran\Show as PendaftaranShow;
// use App\Livewire\Pendaftar\Dashboard as PendaftarDashboard;
use App\Http\Controllers\Modules\PMB\PendaftarDashboardController;
use App\Http\Controllers\Modules\PMB\PendaftarBiodataController;
// use App\Http\Controllers\Modules\PMB\DocumentUploadController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Modules\PMB\AdminSeleksiController;
use App\Http\Controllers\Modules\PMB\AdminPendaftaranController;
use App\Http\Controllers\Modules\PMB\AdmissionCategoryController;
use App\Http\Controllers\Modules\PMB\FinalizeRegistrationController;
use App\Http\Controllers\Pendaftar\ReRegistrationController;
use App\Http\Controllers\Modules\PMB\PaymentVerificationController;
use App\Http\Controllers\Pendaftar\DocumentUploadController;
use App\Http\Controllers\Pendaftar\InstallmentPaymentController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Akademik\Mahasiswa\DashboardController;
use App\Http\Controllers\Modules\PMB\BatchController;


Route::get('/', [HomeController::class, 'index'])->name('home');

// permission minimal manage pmb/role dir pmb
Route::prefix('admin')->middleware(['auth', 'permission:manage pmb'])->name('admin.')->group(function () {
    Route::resource('jalur-pendaftaran', AdmissionCategoryController::class);
    Route::resource('gelombang', BatchController::class);
});

//akses untuk user login ke profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'permission:view pmb'])->name('admin.')->group(function () {


    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/pendaftaran', [AdminPendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{application}', PendaftaranShow::class)->name('pendaftaran.show');


    Route::get('/seleksi', [AdminSeleksiController::class, 'index'])->name('seleksi.index');
    Route::get('/seleksi/data', [AdminSeleksiController::class, 'data'])->name('seleksi.data');
    Route::post('/seleksi/{application}/accept', [AdminSeleksiController::class, 'accept'])->name('seleksi.accept');
    Route::post('/seleksi/{application}/reject', [AdminSeleksiController::class, 'reject'])->name('seleksi.reject');
    Route::get('/diterima', [AcceptedStudentController::class, 'index'])->name('diterima.index');


    Route::get('/verifikasi-pembayaran', [PaymentVerificationController::class, 'index'])->name('payment.index');
    Route::get('/verifikasi-pembayaran/{invoice}', [PaymentVerificationController::class, 'show'])->name('payment.show');
    // Route::post('/verifikasi-pembayaran/{invoice}/approve', [PaymentVerificationController::class, 'approve'])->name('payment.approve');

    //verifikasi pembayaran cicilan untuk registrasi ulang
    Route::post('/verifikasi-pembayaran/installment/{installment}/approve', [PaymentVerificationController::class, 'approveInstallment'])->name('payment.approve');
    Route::post('/verifikasi-pembayaran/installment/{installment}/reject', [PaymentVerificationController::class, 'rejectInstallment'])->name('payment.reject');
    // finalisasi pendaftaran
    Route::post('/diterima/{application}/finalize', [FinalizeRegistrationController::class, 'finalize'])->name('accepted.finalize');

    //mahasiswa
    Route::get('/mahasiswa', [StudentController::class, 'index'])->name('students.index');
});


// Untuk Calon Mahasiswa
Route::middleware('auth', 'role:Camaru')->group(function () {
    Route::get('/camaru', [PendaftarDashboardController::class, 'showDashboard'])->name('pendaftar');
    Route::get('/camaru/biodata', [PendaftarBiodataController::class, 'showDashboard'])->name('pendaftar.biodata');
    Route::get('/camaru/upload-dokumen', [PendaftarBiodataController::class, 'showDocumentUploadForm'])->name('pendaftar.document.form');
    Route::post('/camaru/dashboard/upload-document/{application}', [DocumentUploadController::class, 'store'])->name('pendaftar.document.store');
    Route::get('/camaru/registrasi', [ReRegistrationController::class, 'show'])->name('pendaftar.registrasi');
    Route::post('/camaru/registrasi/pilih-skema', [ReRegistrationController::class, 'choosePaymentScheme'])->name('pendaftar.registrasi.scheme');
    Route::post('/camaru/pembayaran-cicilan/{installment}', [InstallmentPaymentController::class, 'store'])->name('pendaftar.installment.store');
});


// untuk mahasiswa

Route::prefix('akademik')->middleware(['auth', 'role:Mahasiswa'])->name('akademik.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
// No Login


Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.form');
Route::get('/pendaftaran/sukses', function () {
    return view('sukses');
})->name('pendaftaran.sukses');
Route::get('/jalur/{category:slug}', [PendaftaranController::class, 'showCategoryDetail'])->name('pendaftaran.category.detail');

Route::get('/cari-sekolah', function () {
    return view('halaman-pencarian'); // Ganti dengan nama view Anda
});

require __DIR__ . '/auth.php';
