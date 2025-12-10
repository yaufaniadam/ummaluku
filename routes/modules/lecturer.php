<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\KrsApprovalController;
use App\Http\Controllers\Dosen\AdvisedStudentController;
use App\Http\Controllers\Dosen\GradeInputController;

Route::middleware(['auth', 'role:Dosen'])->prefix('dosen')->name('dosen.')->group(function () {

    Route::get('/dosen/dashboard', fn() => 'Dashboard Dosen')
        ->name('dosen.dashboard');

    Route::get('krs-approval', [KrsApprovalController::class, 'index'])->name('krs-approval.index');
    Route::get('dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    Route::get('krs-approval/{student}', [KrsApprovalController::class, 'show'])->name('krs-approval.show');
    Route::get('mahasiswa-bimbingan', [AdvisedStudentController::class, 'index'])->name('advised-students.index');
    Route::get('input-nilai', [GradeInputController::class, 'index'])->name('grades.input.index');

    Route::get('input-nilai/{course_class}', [GradeInputController::class, 'show'])->name('grades.input.show');

});
