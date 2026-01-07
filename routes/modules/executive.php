<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ExecutiveDashboardController;
use App\Http\Controllers\Executive\ExecutiveLecturerController;
use App\Http\Controllers\Executive\ExecutiveStaffController;
use App\Http\Controllers\Executive\ExecutiveStudentController;
use App\Http\Controllers\Executive\ExecutivePMBController;

/*
|--------------------------------------------------------------------------
| Executive Subdomain Routes
|--------------------------------------------------------------------------
|
| Routes for executive subdomain (e.g., executive.ummaluku.test)
| These routes handle executive dashboard and related features
|
*/

// Use pattern that matches any subdomain starting with 'executive'
// This works with Herd/Valet local development and production
Route::domain(config('app.executive_domain', 'executive.ummaluku.test'))
    ->middleware(['auth', 'permission:view-executive-dashboard'])
    ->name('executive.')
    ->group(function () {
        // Root redirect to dashboard
        Route::get('/', function () {
            return redirect()->route('executive.dashboard');
        });

        // Executive Dashboard
        Route::get('/dashboard', [ExecutiveDashboardController::class, 'index'])
            ->name('dashboard');

        // PMB - Penerimaan Mahasiswa Baru (Read-only)
        Route::get('/data-pendaftar', [ExecutivePMBController::class, 'index'])->name('pmb.index');
        Route::get('/data-pendaftar-ajax', function (App\DataTables\ExecutivePMBDataTable $dataTable) {
            return $dataTable->ajax();
        })->name('pmb.data');

        // Students (Read-only)
        Route::get('/students', [ExecutiveStudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [ExecutiveStudentController::class, 'show'])->name('students.show');
       Route::get('/students-data', function (App\DataTables\ExecutiveStudentDataTable $dataTable) {
            return $dataTable->ajax();
        })->name('students.data');

        // Lecturers (Read-only)
        Route::get('/lecturers', [ExecutiveLecturerController::class, 'index'])->name('lecturers.index');
        Route::get('/lecturers/{lecturer}', [ExecutiveLecturerController::class, 'show'])->name('lecturers.show');
        Route::get('/lecturers-data', function (App\DataTables\ExecutiveLecturerDataTable $dataTable) {
            return $dataTable->ajax();
        })->name('lecturers.data');

        // Staff (Read-only)
        Route::get('/staff', [ExecutiveStaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/{staff}', [ExecutiveStaffController::class, 'show'])->name('staff.show');
        Route::get('/staff-data', function (App\DataTables\ExecutiveStaffDataTable $dataTable) {
            return $dataTable->ajax();
        })->name('staff.data');

        // Future executive routes can be added here
        // Example:
        // Route::get('/reports', [ExecutiveReportController::class, 'index'])->name('reports');
        // Route::get('/analytics', [ExecutiveAnalyticsController::class, 'index'])->name('analytics');
    });
