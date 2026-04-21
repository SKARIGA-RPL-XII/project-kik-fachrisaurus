<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetingController extends Controller
{
    /**
     * Reusable: Cek otorisasi pengajar
     */
    private function authorizeTeacher(ClassRoom $kelas): void
    {
        if ($kelas->teacher_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
    }

    /**
     * Reusable: Aturan validasi form
     */
    private function getValidationRules(): array
    {
        return [
            'class_id' => 'required|exists:classes,id',
            'type' => 'required|in:tugas,materi',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topic' => 'nullable|string|max:255',
            'links' => 'nullable|array',
            'links.*' => 'nullable|url',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:5120',
            'deadline' => 'nullable|date',
            'disable_late_submission' => 'nullable|boolean',
            'max_score' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Reusable: Proses upload file array
     */
    private function handleFileUploads(Request $request, array $existingFiles = []): array
    {
        $uploadedFiles = $existingFiles;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $uploadedFiles[] = [
                    'path' => $file->store('meetings', 'public'),
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
        }

        return $uploadedFiles;
    }


    /* ================= CORE METHODS ================= */

    public function create(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:classes,id',
            'type' => 'required|in:tugas,materi'
        ]);

        $kelas = ClassRoom::findOrFail($request->kelas_id);
        $this->authorizeTeacher($kelas);

        $type = $request->type;
        $nextMeetingNumber = $kelas->meetings()->count() + 1;

        return view('pengajar.meetings.create', compact('kelas', 'type', 'nextMeetingNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->getValidationRules());

        $kelas = ClassRoom::findOrFail($request->class_id);
        $this->authorizeTeacher($kelas);

        // Filter null links dan proses upload file
        $validated['links'] = array_filter($request->links ?? []);
        $validated['files'] = $this->handleFileUploads($request);

        // Sesuaikan logika jika tipe 'materi', kosongkan data khusus tugas
        if ($validated['type'] === 'materi') {
            $validated['deadline'] = null;
            $validated['disable_late_submission'] = false;
            $validated['max_score'] = null;
        } else {
            $validated['disable_late_submission'] = $request->has('disable_late_submission');
        }

        $kelas->meetings()->create($validated);

        return redirect()->route('teacher.kelas.show', $kelas->id)
            ->with('success', ucfirst($request->type) . ' berhasil ditambahkan!');
    }

    public function show(Meeting $meeting)
    {
        $submissions = $meeting->submissions()->get();

        $stats = [
            'graded' => $submissions->filter(fn($s) => $s->score !== null)->count(),
            'submitted' => $submissions->filter(fn($s) => $s->submitted_at && $s->score === null)->count(),
            'total' => $submissions->count(),
        ];

        return view('pengajar.meetings.show', compact('meeting', 'stats'));
    }

    public function edit(Meeting $meeting)
    {
        $this->authorizeTeacher($meeting->classRoom);

        $kelas = $meeting->classRoom;
        $type = $meeting->type;

        return view('pengajar.meetings.edit', compact('meeting', 'kelas', 'type'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $this->authorizeTeacher($meeting->classRoom);

        // Untuk update, class_id dan type kita ambil paksa dari data lama agar tidak dimanipulasi
        $request->merge([
            'class_id' => $meeting->class_id,
            'type' => $meeting->type,
        ]);

        $validated = $request->validate($this->getValidationRules());

        // Ambil file lama dan gabungkan dengan file baru
        $existingFiles = is_array($meeting->files) ? $meeting->files : [];

        $validated['links'] = array_filter($request->links ?? []);
        $validated['files'] = $this->handleFileUploads($request, $existingFiles);

        // Reset data jika materinya bukan tugas
        if ($validated['type'] === 'materi') {
            $validated['deadline'] = null;
            $validated['disable_late_submission'] = false;
            $validated['max_score'] = null;
        } else {
            $validated['disable_late_submission'] = $request->has('disable_late_submission');
        }

        $meeting->update($validated);

        return redirect()->route('teacher.kelas.show', $meeting->classRoom->id)
            ->with('success', ucfirst($meeting->type) . ' berhasil diperbarui!');
    }

    public function destroy(Meeting $meeting)
    {
        $this->authorizeTeacher($meeting->classRoom);

        // Hapus fisik file dari storage (Opsional tapi direkomendasikan untuk Clean Code)
        if (is_array($meeting->files)) {
            foreach ($meeting->files as $file) {
                if (isset($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
        }

        $meeting->delete();

        return back()->with('success', 'Pertemuan berhasil dihapus!');
    }
}