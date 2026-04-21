<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        // Bersihkan link (auto tambah https:// kalau belum ada)
        $cleanLinks = [];
        if ($request->has('links')) {
            foreach (array_filter($request->links) as $link) {
                if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
                    $link = "https://" . $link;
                }
                $cleanLinks[] = $link;
            }
        }

        $request->merge(['links' => $cleanLinks]);

        // Validasi
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'content'  => 'required|string',
            'links'    => 'nullable|array',
            'links.*'  => 'url',
            'files'    => 'nullable|array',
            'files.*'  => 'file|max:5120|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
        ]);

        // ✅ Pastikan siswa tergabung di kelas (BUKAN teacher_id)
        $class = ClassRoom::where('id', $validated['class_id'])
            ->whereHas('students', function ($q) {
                $q->where('users.id', auth()->id());
            })
            ->firstOrFail();

        // Upload file
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path'          => $file->store('announcements', 'public'),
                    'size'          => $file->getSize(),
                ];
            }
        }

        // Simpan
        Announcement::create([
            'class_id' => $validated['class_id'],
            'user_id'  => auth()->id(),
            'content'  => $validated['content'],
            'links'    => $cleanLinks,
            'files'    => $uploadedFiles,
        ]);

        // ✅ Redirect ke student, bukan teacher
        return redirect()->route('student.kelas.show', [
            'kelas' => $validated['class_id'],
            'tab'   => 'beranda'
        ])->with('success', 'Pengumuman berhasil diposting.');
    }
}