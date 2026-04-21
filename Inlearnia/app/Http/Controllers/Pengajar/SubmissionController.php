<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Meeting $meeting)
    {
        // Pastikan guru ini memiliki akses ke meeting tsb
        $classRoom = $meeting->classRoom;

        // Ambil semua siswa di kelas ini
        $students = $classRoom->students()->orderBy('name')->get();

        // Ambil semua submission untuk meeting ini, key by student_id
        $submissions = $meeting->submissions()
            ->with('student')
            ->get()
            ->keyBy('student_id');

        // Gabungkan: setiap siswa dapat 1 baris
        $rows = $students->map(function ($student) use ($submissions, $meeting) {
            $sub = $submissions->get($student->id);
            return [
                'student'    => $student,
                'submission' => $sub,
                'status'     => $sub ? $sub->status : 'missing',
            ];
        });

        $stats = [
            'graded'    => $rows->where('status', 'graded')->count(),
            'submitted' => $rows->where('status', 'submitted')->count(),
            'late'      => $rows->where('status', 'late')->count(),
            'missing'   => $rows->where('status', 'missing')->count(),
            'total'     => $rows->count(),
        ];

        return view('pengajar.submissions.index', compact('meeting', 'classRoom', 'rows', 'stats'));
    }

    public function show(Meeting $meeting, Submission $submission)
    {
        $submission->load('student');
        $classRoom = $meeting->classRoom;

        // Navigasi prev/next — hanya yang sudah submit, urut waktu kumpul
        $submittedIds = $meeting->submissions()
            ->orderBy('submitted_at')
            ->pluck('id')
            ->values();

        $currentIndex = $submittedIds->search($submission->id);
        $prevId = $currentIndex > 0 ? $submittedIds[$currentIndex - 1] : null;
        $nextId = $currentIndex < $submittedIds->count() - 1 ? $submittedIds[$currentIndex + 1] : null;

        return view('pengajar.submissions.show', compact(
            'meeting', 'classRoom', 'submission',
            'prevId', 'nextId', 'submittedIds', 'currentIndex'
        ));
    }

    public function grade(Request $request, Meeting $meeting, Submission $submission)
    {
        $request->validate([
            'score'    => ['nullable', 'integer', 'min:0', 'max:' . ($meeting->max_score ?? 100)],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->update([
            'score'    => $request->score,
            'feedback' => $request->feedback,
        ]);

        // Tombol "Simpan & Lanjut Berikutnya"
        if ($request->filled('next_submission_id')) {
            return redirect()
                ->route('teacher.meetings.submissions.show', [
                    $meeting,
                    $request->next_submission_id,
                ])
                ->with('success', 'Penilaian disimpan.');
        }

        return back()->with('success', 'Penilaian berhasil disimpan.');
    }
}