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
    if ($role === 'teacher') return redirect()->route('teacher.dashboard'); // Perbaikan: konsisten nama route prefix
    if ($role === 'student') return redirect()->route('student.dashboard'); // Perbaikan: konsisten nama route prefix
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
        })->name(name: 'profile.index');
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
        // Dashboard
        Route::get('/dashboard', function () {
            return view('pengajar.dashboard');
        })->name('dashboard');

        // List Kelas (Placeholder View)
        Route::get('/kelas', function () {
            return view('pengajar.kelas.index'); // Pastikan file view ini ada
        })->name('kelas.index');

        // Jadwal (Placeholder View)
        Route::get('/jadwal', function () {
            return view('pengajar.jadwal.index'); // Pastikan file view ini ada
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

        // List Kelas (Placeholder View)
        Route::get('/kelas', function () {
            return view('siswa.kelas.index'); // Pastikan file view ini ada
        })->name('kelas.index');

        // Jadwal (Placeholder View)
        Route::get('/jadwal', function () {
            return view('siswa.jadwal.index'); // Pastikan file view ini ada
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