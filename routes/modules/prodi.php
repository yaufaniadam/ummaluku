<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Prodi\Dashboard;
use App\Livewire\Prodi\Course\Index as CourseIndex;
use App\Livewire\Prodi\CourseClass\Index as CourseClassIndex;
use App\Livewire\Prodi\CourseClass\Form as CourseClassForm;
use App\Livewire\Prodi\Krs\Index as KrsApprovalIndex;
use App\Livewire\Prodi\Krs\ApprovalDetail;

Route::prefix('prodi')->name('prodi.')->middleware(['auth', 'role:Kaprodi|Staf Prodi'])->group(function () {

    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Manajemen Matakuliah (Read Only)
    Route::get('/courses', CourseIndex::class)->name('course.index');

    // Manajemen Kelas
    Route::get('/classes', CourseClassIndex::class)->name('course-class.index');
    Route::get('/classes/create', CourseClassForm::class)->name('course-class.create');
    Route::get('/classes/{courseClass}/edit', CourseClassForm::class)->name('course-class.edit');

    // Persetujuan KRS (Khusus Kaprodi)
    Route::get('/krs-approval', KrsApprovalIndex::class)
        ->name('krs-approval.index')
        ->middleware('role:Kaprodi'); // Ensure only Kaprodi can access approval

    Route::get('/krs-approval/{student}', ApprovalDetail::class)
        ->name('krs-approval.detail')
        ->middleware('role:Kaprodi');
});
