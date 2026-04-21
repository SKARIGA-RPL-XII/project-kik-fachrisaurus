<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher   = Auth::user(); // ← ganti dari Auth::id() biar bisa pass ke view
        $teacherId = $teacher->id;

        $totalKelasSaya = ClassRoom::where('teacher_id', $teacherId)->count();

        $totalSiswaDiajar = User::where('role', 'student')
            ->whereHas('classes', fn($q) => $q->where('teacher_id', $teacherId))
            ->count();

        $myClasses = ClassRoom::where('teacher_id', $teacherId)
            ->with(['subject'])
            ->withCount('students')
            ->latest() // ← tambah latest() biar urut terbaru
            ->get();

        // Kosongkan dulu, isi nanti kalau sudah ada tabel submissions
        $totalTugasBelumDinilai = 0;
        $latestSubmissions      = collect([]);

        return view('pengajar.dashboard', compact(
            'teacher',
            'totalKelasSaya',
            'totalSiswaDiajar',
            'totalTugasBelumDinilai',
            'myClasses',
            'latestSubmissions',
        ));
    }
}