<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\KrsController;
use App\Http\Controllers\Mahasiswa\ProfileController as MhsProfileController;
use App\Http\Controllers\Mahasiswa\KeuanganController;
use App\Http\Controllers\Mahasiswa\PaymentConfirmationController;
use App\Http\Controllers\Mahasiswa\HasilStudiController;

Route::prefix('mahasiswa')->middleware(['auth', 'role:Mahasiswa'])->name('mahasiswa.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('krs', [KrsController::class, 'index'])->name('krs.index');
    Route::get('krs/proses', [KrsController::class, 'prosesKrs'])->name('krs.proses');
    Route::get('profil', [MhsProfileController::class, 'index'])->name('profil.index');
    Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('keuangan/{invoice}', [KeuanganController::class, 'show'])->name('keuangan.show');
    Route::get('keuangan/{invoice}/confirm', [PaymentConfirmationController::class, 'create'])->name('keuangan.confirm.create');
    Route::post('keuangan/{invoice}/confirm', [PaymentConfirmationController::class, 'store'])->name('keuangan.confirm');
    Route::get('hasil-studi', [HasilStudiController::class, 'index'])->name('hasil-studi.index');
    Route::get('krs-aktif/cetak', [KrsController::class, 'printPdf'])->name('krs.aktif.print');
});
