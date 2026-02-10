<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        // Statistik
        $totalKelas = ClassRoom::where('school_id', $schoolId)->count();
        $totalPengajar = User::where('school_id', $schoolId)->where('role', 'teacher')->count();
        $totalSiswa = User::where('school_id', $schoolId)->where('role', 'student')->count();

        // 5 pengajar terbaru
        $latestTeachers = User::where('school_id', $schoolId)
            ->where('role', 'teacher')
            ->latest()
            ->take(5)
            ->get();

        // 5 kelas terbaru
        $latestClasses = ClassRoom::where('school_id', $schoolId)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKelas',
            'totalPengajar',
            'totalSiswa',
            'latestTeachers',
            'latestClasses'
        ));
    }
}
