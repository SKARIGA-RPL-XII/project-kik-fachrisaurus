<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        // Filter tahun (default tahun sekarang)
        $selectedYear = $request->get('year', date('Y'));

        // =========================
        // STATISTIK CARD
        // =========================
        $totalKelas = ClassRoom::where('school_id', $schoolId)->count();

        $totalPengajar = User::where('school_id', $schoolId)
            ->where('role', 'teacher')
            ->count();

        $totalSiswa = User::where('school_id', $schoolId)
            ->where('role', 'student')
            ->count();

        // =========================
        // CHART DATA (SISWA PER BULAN)
        // =========================
        $monthlyStudents = array_fill(0, 12, 0);

        $studentsData = User::where('school_id', $schoolId)
            ->where('role', 'student')
            ->whereYear('created_at', $selectedYear)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        foreach ($studentsData as $month => $total) {
            $monthlyStudents[$month - 1] = $total;
        }

        // =========================
        // LIST TAHUN FILTER
        // =========================
        $availableYears = range(date('Y'), date('Y') - 4);

        // =========================
        // TEACHER TERBARU
        // =========================
        $latestTeachers = User::where('school_id', $schoolId)
            ->where('role', 'teacher')
            ->latest()
            ->take(5)
            ->get();

        // =========================
        // 🔥 KELAS TERBARU (INI FIX ERROR KAMU)
        // =========================
        $latestClasses = ClassRoom::with(['teacher', 'subject'])
            ->withCount('students')
            ->where('school_id', $schoolId)
            ->latest()
            ->take(5)
            ->get();

        // =========================
        // RETURN VIEW
        // =========================
        return view('admin.dashboard', compact(
            'totalKelas',
            'totalPengajar',
            'totalSiswa',
            'latestTeachers',
            'latestClasses', // 🔥 WAJIB
            'monthlyStudents',
            'selectedYear',
            'availableYears'
        ));
    }
}