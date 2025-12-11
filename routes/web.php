<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\Admin\AcademicEventController;
use App\Http\Controllers\Modules\PMB\PendaftaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Common Auth Routes (Profile, Notifications)
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

// Public Pages (PMB)
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.form');

Route::get('/pendaftaran/sukses', function () {
    return view('sukses');
})->name('pendaftaran.sukses');

Route::get('/admisi/{category:slug}', [PendaftaranController::class, 'showCategoryDetail'])->name('pendaftaran.category.detail');


// Load Module Routes
require __DIR__ . '/modules/admin.php';
require __DIR__ . '/modules/student.php';
require __DIR__ . '/modules/lecturer.php';
require __DIR__ . '/modules/camaru.php';
require __DIR__ . '/modules/prodi.php';

require __DIR__ . '/auth.php';
