<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Livewire\Master\WorkUnit\Index as WorkUnitIndex;
use App\Http\Controllers\Admin\ExecutiveDashboardController;
use App\Http\Controllers\SDM\SDMDashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\DataTables\LecturerDataTable;
use App\Http\Controllers\Admin\StaffController;
use App\DataTables\StaffDataTable;
use App\Http\Controllers\Keuangan\KeuanganDashboardController;
use App\Http\Controllers\Admin\TuitionFeeController;
use App\DataTables\TuitionFeeDataTable;
use App\Http\Controllers\Admin\FeeComponentController;
use App\DataTables\FeeComponentDataTable;
use App\Http\Controllers\Admin\AcademicPaymentController;
use App\DataTables\AcademicInvoiceDataTable;
use App\Http\Controllers\Akademik\AkademikDashboardController;
use App\Http\Controllers\Admin\CurriculumController;
use App\DataTables\CurriculumDataTable;
use App\Http\Controllers\Admin\CurriculumCourseController;
use App\Http\Controllers\Admin\CourseController;
use App\DataTables\CourseDataTable;
use App\Http\Controllers\Admin\AcademicYearController;
use App\DataTables\AcademicYearDataTable;
use App\Http\Controllers\Admin\CourseClassController;
use App\DataTables\CourseClassDataTable;
use App\Models\AcademicYear;
use App\Models\Program;
use App\Http\Controllers\Admin\StudentController;
use App\DataTables\StudentDataTable;
use App\Http\Controllers\Admin\AcademicEventController;
use App\DataTables\AcademicEventDataTable;
use App\Http\Controllers\Modules\PMB\AdmissionCategoryController;
use App\Http\Controllers\Modules\PMB\BatchController;
use App\Http\Controllers\Modules\PMB\PMBDashboardController;
use App\Http\Controllers\Modules\PMB\AdminPendaftaranController;
use App\Livewire\Admin\Pendaftaran\Show as PendaftaranShow;
use App\Http\Controllers\Modules\PMB\AdminSeleksiController;
use App\Http\Controllers\Modules\PMB\AcceptedStudentController;
use App\Http\Controllers\Modules\PMB\PaymentVerificationController;
use App\Http\Controllers\Modules\PMB\FinalizeRegistrationController;


Route::prefix('admin')->middleware(['auth', 'role:Super Admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin/system')->middleware(['auth', 'role:Super Admin'])->name('admin.system.')->group(function () {
    Route::get('/roles', App\Livewire\System\Role\Index::class)->name('roles.index');
    Route::get('/roles/create', App\Livewire\System\Role\Form::class)->name('roles.create');
    Route::get('/roles/{role}/edit', App\Livewire\System\Role\Form::class)->name('roles.edit');

    Route::get('/users', App\Livewire\System\User\Index::class)->name('users.index');
    Route::get('/users/create', App\Livewire\System\User\Form::class)->name('users.create');
    Route::get('/users/{user}/edit', App\Livewire\System\User\Form::class)->name('users.edit');
});

Route::prefix('master')->middleware(['auth', 'role:Super Admin|Admin'])->name('master.')->group(function () {
    Route::get('/work-units', WorkUnitIndex::class)->name('work-units.index');
    Route::get('/programs', App\Livewire\Master\Program\Index::class)->name('programs.index');
    Route::get('/programs/{program}/manage-head', App\Livewire\Master\Program\HeadManager::class)->name('programs.manage-head');
});

Route::prefix('admin/executive')->middleware(['auth', 'permission:view-executive-dashboard'])->name('admin.executive.')->group(function () {
    Route::get('/dashboard', [ExecutiveDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin/sdm')->middleware(['auth', 'permission:dosen-list'])->name('admin.sdm.')->group(function () {
    Route::get('/dashboard', [SDMDashboardController::class, 'index'])->name('dashboard');
    Route::post('lecturers/import', [LecturerController::class, 'import'])->name('lecturers.import');
    Route::resource('lecturers', LecturerController::class);
    Route::get('lecturers-data', function (LecturerDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('lecturers.data');

    Route::resource('staff', StaffController::class);
    Route::get('staff-data', function (StaffDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('staff.data');

    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/', App\Livewire\Sdm\Master\Index::class)->name('index');
    });
});

Route::prefix('admin/keuangan')->middleware(['auth', 'permission:biaya-list'])->name('admin.keuangan.')->group(function () {
    Route::get('/dashboard', [KeuanganDashboardController::class, 'index'])->name('dashboard');
    Route::resource('tuition-fees', TuitionFeeController::class);
    Route::get('tuition-fees-data', function (TuitionFeeDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('tuition-fees.data');
    Route::resource('fee-components', FeeComponentController::class);
    Route::get('fee-components-data', function (FeeComponentDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('fee-components.data');

    Route::post('tuition-fees/duplicate', [TuitionFeeController::class, 'duplicate'])->name('tuition-fees.duplicate');

    Route::post('payment-verification/{payment}/approve', [AcademicPaymentController::class, 'approve'])->name('payment-verification.approve');
    Route::post('payment-verification/{payment}/reject', [AcademicPaymentController::class, 'reject'])->name('payment-verification.reject');


    Route::resource('payment-verification', AcademicPaymentController::class)->only(['index']);
    Route::get('payment-verification-data', function(AcademicInvoiceDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('payment-verification.data');
});


Route::prefix('admin/akademik')->middleware(['auth'])->name('admin.akademik.')->group(function () {
    Route::get('/dashboard', [AkademikDashboardController::class, 'index'])->name('dashboard');

    Route::resource('curriculums', CurriculumController::class);
    Route::get('curriculums-data', function (CurriculumDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('curriculums.data');

    Route::get('curriculums/{curriculum}/manage-courses', [CurriculumCourseController::class, 'index'])->name('curriculums.manage-courses');
    Route::get('curriculums/{curriculum}/courses', [CurriculumCourseController::class, 'index'])->name('curriculums.courses.index');
    Route::get('curriculums/{curriculum}/courses/add', [CurriculumCourseController::class, 'add'])->name('curriculums.courses.add');

    Route::post('curriculums/{curriculum}/courses', [CurriculumCourseController::class, 'store'])->name('curriculums.courses.store');
    Route::delete('curriculums/{curriculum}/courses/{course}', [CurriculumCourseController::class, 'destroy'])->name('curriculums.courses.destroy');

    Route::delete('curriculums/{curriculum}/courses/bulk-delete', [CurriculumCourseController::class, 'bulkDestroy'])->name('curriculums.courses.bulkDestroy');

    Route::post('/courses/bulk-delete', [CourseController::class, 'bulkDelete'])
        ->name('courses.bulkDelete');

    Route::resource('courses', CourseController::class);
    Route::get('courses-data', function (CourseDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('courses.data');

    Route::post('courses/import', [CourseController::class, 'import'])->name('courses.import');


    Route::resource('academic-years', AcademicYearController::class);
    Route::get('academic-years-data', function (AcademicYearDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('academic-years.data');
    Route::resource('academic-years.programs.course-classes', CourseClassController::class)->except(['show']);
    Route::get('academic-years/{academic_year}/programs/{program}/course-classes-data', function (CourseClassDataTable $dataTable, AcademicYear $academic_year, Program $program) {
        $dataTable->academic_year_id = $academic_year->id;
        $dataTable->program_id = $program->id;
        return $dataTable->ajax();
    })->name('academic-years.programs.course-classes.data');

    Route::post(
        'academic-years/{academic_year}/programs/{program}/course-classes/{course}/quick-create',
        [CourseClassController::class, 'quickCreate']
    )->name('academic-years.programs.course-classes.quickCreate');

    Route::post(
        'academic-years/{academic_year}/programs/{program}/course-classes/auto-generate',
        [CourseClassController::class, 'autoGenerate']
    )->name('academic-years.programs.course-classes.autoGenerate');

    Route::post(
        'academic-years/{academic_year}/programs/{program}/course-classes/copy-previous',
        [CourseClassController::class, 'copyFromPrevious']
    )->name('academic-years.programs.course-classes.copyFromPrevious');

    Route::get('students/import', [StudentController::class, 'showImportForm'])->name('students.import.form');
    Route::post('students/import', [StudentController::class, 'importOld'])->name('students.import.old');
    Route::resource('students', StudentController::class);
    Route::get('students-data', function (StudentDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('students.data');
    Route::resource('academic-events', AcademicEventController::class);
    Route::get('academic-events-data', function (AcademicEventDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('academic-events.data');
});

// permission minimal manage pmb/role dir pmb
Route::prefix('admin/pmb')->middleware(['auth', 'permission:manage pmb settings'])->name('admin.pmb.')->group(function () {
    Route::resource('jalur-pendaftaran', AdmissionCategoryController::class);
    Route::resource('gelombang', BatchController::class);
});

// akses untuk user dengan permission view pmb
Route::prefix('admin/pmb')->middleware(['auth', 'permission:view applications'])->name('admin.pmb.')->group(function () {
    Route::get('/dashboard', [PMBDashboardController::class, 'index'])->name('dashboard');

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
});
