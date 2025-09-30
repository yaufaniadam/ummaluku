<?php

use App\Http\Controllers\Modules\PMB\AcceptedStudentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Modules\PMB\PendaftaranController;
use App\Livewire\Admin\Pendaftaran\Show as PendaftaranShow;
use App\Http\Controllers\Modules\PMB\PendaftarDashboardController;
use App\Http\Controllers\Modules\PMB\PendaftarBiodataController;

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
// use App\Http\Controllers\Akademik\Mahasiswa\DashboardController;
use App\Http\Controllers\Modules\PMB\BatchController;
use App\Http\Controllers\NotificationsController;

use App\Http\Controllers\Admin\LecturerController;
use App\DataTables\LecturerDataTable;

use App\Http\Controllers\Admin\CurriculumController;
use App\DataTables\CurriculumDataTable;

use App\Http\Controllers\Admin\CourseController;
use App\DataTables\CourseDataTable;

use App\Http\Controllers\Admin\AcademicYearController;
use App\DataTables\AcademicYearDataTable;

use App\Http\Controllers\Admin\CourseClassController;
use App\DataTables\CourseClassDataTable;
use App\Models\AcademicYear;
use App\Models\Program;

use App\DataTables\StudentDataTable;

use App\Http\Controllers\Admin\TuitionFeeController;
use App\DataTables\TuitionFeeDataTable;

use App\Http\Controllers\Admin\FeeComponentController;
use App\DataTables\FeeComponentDataTable;

use App\Http\Controllers\Admin\AcademicEventController;
use App\DataTables\AcademicEventDataTable;
use App\Http\Controllers\AcademicCalendarController;

use App\Http\Controllers\Akademik\AkademikDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SDM\SDMDashboardController;
use App\Http\Controllers\Modules\PMB\PMBDashboardController;
use App\Http\Controllers\Keuangan\KeuanganDashboardController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware(['auth', 'permission:manage pmb'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin/sdm')->middleware(['auth', 'permission:dosen-list'])->name('admin.sdm.')->group(function () {
    Route::get('/dashboard', [SDMDashboardController::class, 'index'])->name('dashboard');
    Route::post('lecturers/import', [LecturerController::class, 'import'])->name('lecturers.import');
    Route::resource('lecturers', LecturerController::class);
    Route::get('lecturers-data', function (LecturerDataTable $dataTable) {
        return $dataTable->ajax();
    })->name('lecturers.data');
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
});

//akses untuk user login ke profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('notifications/get', [NotificationsController::class, 'getNotificationsData'])->name('notifications.get');
    Route::get('notifications/show', [NotificationsController::class, 'showAll'])->name('notifications.show');
    Route::post('notifications/{id}/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('notifications/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/kalender-akademik', [AcademicCalendarController::class, 'index'])->name('calendar.index');
    Route::get('/api/academic-events', [AcademicEventController::class, 'feed'])->name('api.academic-events.feed');
});

use App\Http\Controllers\Admin\CurriculumCourseController; 

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
Route::prefix('admin/pmb')->middleware(['auth', 'permission:manage pmb'])->name('admin.pmb.')->group(function () {
    Route::resource('jalur-pendaftaran', AdmissionCategoryController::class);
    Route::resource('gelombang', BatchController::class);
});

// akses untuk user dengan permission view pmb
Route::prefix('admin/pmb')->middleware(['auth', 'permission:view pmb'])->name('admin.pmb.')->group(function () {
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
use App\Http\Controllers\Mahasiswa\KrsController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\ProfileController as MhsProfileController;

Route::prefix('mahasiswa')->middleware(['auth', 'role:Mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); // <-- Route ini
    Route::get('krs', [KrsController::class, 'index'])->name('krs.index');
    Route::get('profil', [MhsProfileController::class, 'index'])->name('profil.index');
});

//untuk dosen
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\KrsApprovalController;
use App\Http\Controllers\Dosen\AdvisedStudentController;
use App\Http\Controllers\Dosen\GradeInputController;

Route::middleware(['auth', 'role:Dosen'])->prefix('dosen')->name('dosen.')->group(function () {

    Route::get('/dosen/dashboard', fn() => 'Dashboard Dosen')
        ->name('dosen.dashboard');

    Route::get('krs-approval', [KrsApprovalController::class, 'index'])->name('krs-approval.index');
    Route::get('dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    Route::get('krs-approval', [KrsApprovalController::class, 'index'])->name('krs-approval.index');
    Route::get('krs-approval/{student}', [KrsApprovalController::class, 'show'])->name('krs-approval.show');
    Route::get('mahasiswa-bimbingan', [AdvisedStudentController::class, 'index'])->name('advised-students.index');
    Route::get('input-nilai', [GradeInputController::class, 'index'])->name('grades.input.index');
});

// No Login
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.form');

Route::get('/pendaftaran/sukses', function () {
    return view('sukses');
})->name('pendaftaran.sukses');

Route::get('/admisi/{category:slug}', [PendaftaranController::class, 'showCategoryDetail'])->name('pendaftaran.category.detail');

require __DIR__ . '/auth.php';
