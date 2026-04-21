<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student   = Auth::user();
        $studentId = $student->id;

        $totalKelas = $student->classes()->count();

        $totalTugasDikumpulkan = Submission::where('student_id', $studentId)
            ->whereNotNull('submitted_at')
            ->count();

        $totalTugasBelumDikumpulkan = Meeting::where('type', 'tugas')
            ->whereHas('classRoom.students', fn($q) =>
                $q->where('student_id', $studentId)
            )->whereDoesntHave('submissions', fn($q) =>
                $q->where('student_id', $studentId)
                    ->whereNotNull('submitted_at')
            )->count();

        $myClasses = $student->classes()
            ->with(['teacher', 'subject'])
            ->withCount('students')
            ->latest()
            ->get();

        $latestMeetings = Meeting::where('type', 'tugas')
            ->whereHas('classRoom.students', fn($q) =>
                $q->where('student_id', $studentId)
            )->whereDoesntHave('submissions', fn($q) =>
                $q->where('student_id', $studentId)
                    ->whereNotNull('submitted_at')
            )->with(['classRoom'])
            ->latest()
            ->take(5)
            ->get();

        return view('siswa.dashboard', compact(
            'student',
            'totalKelas',
            'totalTugasDikumpulkan',
            'totalTugasBelumDikumpulkan',
            'myClasses',
            'latestMeetings',
        ));
    }
}