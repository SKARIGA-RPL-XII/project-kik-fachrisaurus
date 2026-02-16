<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Public Route & OTP
|--------------------------------------------------------------------------
*/
Route::post('/send-otp', [RegisteredUserController::class, 'sendOtp'])->name('otp.send');

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect Logic
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'teacher') return redirect()->route('teacher.dashboard');
    if ($role === 'student') return redirect()->route('student.dashboard');
    return abort(403);
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
        Route::resource('users', UserController::class);
        Route::resource('mapel', SubjectController::class)->parameters(['mapel' => 'subject']);
        Route::resource('kelas', ClassRoomController::class);
        
        Route::get('/my-profile', function () {
            return view('admin.profile.index');
        })->name('profile.index'); // Hapus param name: yang tidak perlu
    });

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES (PENGAJAR)
|--------------------------------------------------------------------------
*/
// Import Controller Pengajar
use App\Http\Controllers\Pengajar\ClassRoomController as PengajarClassController;
use App\Http\Controllers\Pengajar\MeetingController;      // <-- TAMBAHKAN INI
use App\Http\Controllers\Pengajar\AnnouncementController; // <-- TAMBAHKAN INI

Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', function () {
            return view('pengajar.dashboard');
        })->name('dashboard');

        // List Kelas
        Route::get('/kelas', [PengajarClassController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/{kelas}', [PengajarClassController::class, 'show'])->name('kelas.show');

        // --- TAMBAHAN ROUTE UNTUK PERTEMUAN (MEETINGS) ---
        Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
        Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
        Route::get('/meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
        Route::put('/meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
        Route::delete('/meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');

        // --- TAMBAHAN ROUTE UNTUK PENGUMUMAN (ANNOUNCEMENTS) ---
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Jadwal
        Route::get('/jadwal', function () {
            return view('pengajar.jadwal.index'); 
        })->name('jadwal.index');

        // Profile
        Route::get('/my-profile', function () {
            return view('pengajar.profile.index');
        })->name('profile.index');
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
        // Dashboard
        Route::get('/dashboard', function () {
            return view('siswa.dashboard');
        })->name('dashboard');

        // List Kelas
        Route::get('/kelas', function () {
            return view('siswa.kelas.index');
        })->name('kelas.index');

        // Jadwal
        Route::get('/jadwal', function () {
            return view('siswa.jadwal.index');
        })->name('jadwal.index');

        // Profile
        Route::get('/my-profile', function () {
            return view('siswa.profile.index');
        })->name('profile.index');
    });

/*
|--------------------------------------------------------------------------
| PROFILE SETTINGS (GLOBAL)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';