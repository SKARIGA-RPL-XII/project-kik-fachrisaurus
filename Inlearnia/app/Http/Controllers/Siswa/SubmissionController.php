<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request, $meeting)  // ← terima $meeting dari route
    {
        $request->validate([
            'content_text' => 'nullable|string',
            'links'        => 'nullable|array',
            'links.*'      => 'nullable|url',
            'files'        => 'nullable|array',
            'files.*'      => 'file|max:5120',
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $uploadedFiles[] = [
                    'path'          => $file->store('submissions', 'public'),
                    'original_name' => $file->getClientOriginalName(),
                    'size'          => $file->getSize(),
                ];
            }
        }

        Submission::updateOrCreate(
            [
                'meeting_id' => $meeting,        // ← dari route parameter
                'student_id' => auth()->id(),
            ],
            [
                'content_text' => $request->content_text,
                'links'        => array_filter($request->links ?? []),
                'files'        => $uploadedFiles,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan');
    }

    public function cancel($meeting)  // ← konsisten pakai nama yang sama
    {
        $submission = Submission::where('meeting_id', $meeting)
            ->where('student_id', auth()->id())
            ->first();

        if (!$submission) {
            return back()->with('error', 'Submission tidak ditemukan');
        }

        if (is_array($submission->files)) {
            foreach ($submission->files as $file) {
                if (isset($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
        }

        $submission->delete();

        return back()->with('success', 'Pengumpulan berhasil dibatalkan');
    }
}