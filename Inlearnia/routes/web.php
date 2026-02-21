<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\SubjectController;

// Admin Controllers
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClassRoomController as AdminClassRoomController;

// Teacher Controllers
use App\Http\Controllers\Pengajar\ClassRoomController as TeacherClassRoomController;
use App\Http\Controllers\Pengajar\MeetingController;
use App\Http\Controllers\Pengajar\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Public Route & OTP
|--------------------------------------------------------------------------
*/
Route::post('/send-otp', [RegisteredUserController::class, 'sendOtp'])->name('otp.send');
Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect Logic
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class);
        Route::resource('mapel', SubjectController::class)->parameters(['mapel' => 'subject']);
        Route::resource('kelas', AdminClassRoomController::class);

        Route::view('/my-profile', 'admin.profile.index')->name('profile.index');
    });

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES (PENGAJAR)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::view('/dashboard', 'pengajar.dashboard')->name('dashboard');

        // List Kelas
        Route::controller(TeacherClassRoomController::class)->group(function () {
            Route::get('/kelas', 'index')->name('kelas.index');
            Route::get('/kelas/{kelas}', 'show')->name('kelas.show');

            Route::delete('/kelas/{kelas}/students/{student}', 'removeStudent')->name('kelas.removeStudent');
        });

        // Pertemuan (Meetings) - Hanya except index (karena listnya ada di dalam detail kelas)
        Route::resource('meetings', MeetingController::class)->except(['index']);

        // Pengumuman (Announcements)
        Route::controller(AnnouncementController::class)->group(function () {
            Route::post('/announcements', 'store')->name('announcements.store');
            Route::put('/announcements/{announcement}', 'update')->name('announcements.update'); // ✅ INI
            Route::delete('/announcements/{announcement}', 'destroy')->name('announcements.destroy');
        });

        // Menu Lainnya
        Route::view('/jadwal', 'pengajar.jadwal.index')->name('jadwal.index');
        Route::view('/my-profile', 'pengajar.profile.index')->name('profile.index');
    });

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES (SISWA)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::view('/dashboard', 'siswa.dashboard')->name('dashboard');
        Route::view('/kelas', 'siswa.kelas.index')->name('kelas.index');
        Route::view('/jadwal', 'siswa.jadwal.index')->name('jadwal.index');
        Route::view('/my-profile', 'siswa.profile.index')->name('profile.index');
    });

/*
|--------------------------------------------------------------------------
| PROFILE SETTINGS (GLOBAL)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'edit')->name('profile.edit');
    Route::patch('/profile', 'update')->name('profile.update');
    Route::delete('/profile', 'destroy')->name('profile.destroy');
});

require __DIR__ . '/auth.php';