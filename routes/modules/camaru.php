<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Modules\PMB\PendaftarDashboardController;
use App\Http\Controllers\Modules\PMB\PendaftarBiodataController;
use App\Http\Controllers\Pendaftar\DocumentUploadController;
use App\Http\Controllers\Pendaftar\ReRegistrationController;
use App\Http\Controllers\Pendaftar\InstallmentPaymentController;

// Untuk Calon Mahasiswa
Route::middleware(['auth', 'role:Camaru'])->group(function () {
    Route::get('/camaru', [PendaftarDashboardController::class, 'showDashboard'])->name('pendaftar');
    Route::get('/camaru/biodata', [PendaftarBiodataController::class, 'showDashboard'])->name('pendaftar.biodata');
    Route::get('/camaru/upload-dokumen', [PendaftarBiodataController::class, 'showDocumentUploadForm'])->name('pendaftar.document.form');
    Route::post('/camaru/dashboard/upload-document/{application}', [DocumentUploadController::class, 'store'])->name('pendaftar.document.store');
    Route::get('/camaru/registrasi', [ReRegistrationController::class, 'show'])->name('pendaftar.registrasi');
    Route::post('/camaru/registrasi/pilih-skema', [ReRegistrationController::class, 'choosePaymentScheme'])->name('pendaftar.registrasi.scheme');
    Route::post('/camaru/pembayaran-cicilan/{installment}', [InstallmentPaymentController::class, 'store'])->name('pendaftar.installment.store');
});
