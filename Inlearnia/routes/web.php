<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::post('/send-otp', [RegisteredUserController::class, 'sendOtp'])
    ->name('otp.send');

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect Sesuai Role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($role === 'teacher') {
        return redirect()->route('pengajar.dashboard');
    }

    return redirect()->route('siswa.dashboard');
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

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD Users
        Route::resource('users', UserController::class);

        // CRUD Mapel (Subjects)
        Route::resource('mapel', SubjectController::class)->parameters([
            'mapel' => 'subject'
        ]);

        // CRUD Kelas
        Route::resource('kelas', ClassRoomController::class);

        Route::get('/my-profile', function () {
            return view('admin.myprofile.index');
        })->name('myprofile.index');

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
        Route::get('/dashboard', function () {
            return view('pengajar.dashboard');
        })->name('dashboard');
        Route::get('/my-profile', function () {
            return view('pengajar.myprofile.index');
        })->name('myprofile.index');

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
        Route::get('/dashboard', function () {
            return view('siswa.dashboard');
        })->name('dashboard');
        Route::get('/my-profile', function () {
            return view('siswa.myprofile.index');
        })->name('myprofile.index');

    });

/*
|--------------------------------------------------------------------------
| PROFILE (GLOBAL)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
