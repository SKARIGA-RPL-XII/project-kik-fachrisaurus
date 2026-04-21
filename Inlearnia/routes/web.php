<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\SubjectController;

// Admin Controllers
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClassRoomController as AdminClassRoomController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Teacher Controllers
use App\Http\Controllers\Pengajar\ClassRoomController as TeacherClassRoomController;
use App\Http\Controllers\Pengajar\MeetingController as TeacherMeetingController;
use App\Http\Controllers\Pengajar\AnnouncementController as TeacherAnnouncementController;
use App\Http\Controllers\Pengajar\SubmissionController as TeacherSubmissionController;

// Student Controllers
use App\Http\Controllers\Siswa\ClassRoomController as SiswaClassRoomController;
use App\Http\Controllers\Siswa\MeetingController as SiswaMeetingController;
use App\Http\Controllers\Siswa\SubmissionController as SiswaSubmissionController;
use App\Http\Controllers\Siswa\AnnouncementController as SiswaAnnouncementController;

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

        Route::get('/my-profile', [AdminProfileController::class, 'index'])->name('profile.index');

        Route::get('/my-profile/school/edit', [AdminProfileController::class, 'editSchool'])
            ->name('profile.school.edit');

        Route::patch('/my-profile/school', [AdminProfileController::class, 'updateSchool'])
            ->name('profile.school.update');

        Route::patch('/my-profile', [AdminProfileController::class, 'update'])
            ->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        // UPDATE: Sekarang menggunakan Controller, bukan Route::view lagi
        Route::get('/dashboard', [App\Http\Controllers\Pengajar\TeacherDashboardController::class, 'index'])->name('dashboard');

        Route::controller(TeacherClassRoomController::class)->group(function () {
            Route::get('/kelas', 'index')->name('kelas.index');
            Route::get('/kelas/{kelas}', 'show')->name('kelas.show');
            Route::delete('/kelas/{kelas}/students/{student}', 'removeStudent')->name('kelas.removeStudent');
        });

        Route::resource('meetings', TeacherMeetingController::class)->except(['index']);

        Route::controller(TeacherSubmissionController::class)->group(function () {
            Route::get('/meetings/{meeting}/submissions', 'index')
                ->name('meetings.submissions.index');
            Route::get('/meetings/{meeting}/submissions/{submission}', 'show')
                ->name('meetings.submissions.show');
            Route::patch('/meetings/{meeting}/submissions/{submission}/grade', 'grade')
                ->name('meetings.submissions.grade');
        });

        Route::controller(TeacherAnnouncementController::class)->group(function () {
            Route::post('/announcements', 'store')->name('announcements.store');
            Route::put('/announcements/{announcement}', 'update')->name('announcements.update');
            Route::delete('/announcements/{announcement}', 'destroy')->name('announcements.destroy');
        });

        Route::view('/jadwal', 'pengajar.jadwal.index')->name('jadwal.index');

        // Profile Routes
        Route::get('/my-profile', [AdminProfileController::class, 'indexPengajar'])->name('profile.index');
        Route::patch('/my-profile', [AdminProfileController::class, 'update'])->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Siswa\StudentDashboardController::class, 'index'])->name('dashboard');

        Route::view('/jadwal', 'siswa.jadwal.index')->name('jadwal.index');
        Route::view('/my-profile', 'siswa.profile.index')->name('profile.index');

        // CLASSROOM
        Route::controller(SiswaClassRoomController::class)->group(function () {
            Route::get('/kelas', 'index')->name('kelas.index');
            Route::get('/kelas/{kelas}', 'show')->name('kelas.show');
        });

        // MEETING (DETAIL MATERI/TUGAS)
        Route::controller(SiswaMeetingController::class)->group(function () {
            Route::get('/meetings/{meeting}', 'show')->name('meetings.show');
            Route::post('/meetings/{meeting}/summary', 'generateSummary')->name('meetings.summary');

        });

        // SUBMISSION
        Route::controller(SiswaSubmissionController::class)->group(function () {
            Route::post('/meetings/{meeting}/submit', 'store')->name('meetings.submit');
            Route::delete('/meetings/{meeting}/cancel', 'cancel')->name('meetings.cancel');
        });

        Route::post('/announcements', [SiswaAnnouncementController::class, 'store'])
            ->name('announcements.store');
        Route::delete('/announcements/{announcement}', [SiswaAnnouncementController::class, 'destroy'])
            ->name('announcements.destroy');

        // PROFILE ROUTES
        Route::get('/my-profile', [AdminProfileController::class, 'indexSiswa'])->name('profile.index');
        Route::patch('/my-profile', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::get('/dashboard', [App\Http\Controllers\Siswa\StudentDashboardController::class, 'index'])->name('dashboard');

    });

require __DIR__ . '/auth.php';