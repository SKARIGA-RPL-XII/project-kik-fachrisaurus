<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
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

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'content'  => 'required|string',
            'links'    => 'nullable|array',
            'links.*'  => 'url',
            'files'    => 'nullable|array',
            'files.*'  => 'file|max:5120|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
        ]);

        ClassRoom::where('id', $validated['class_id'])
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

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

        Announcement::create([
            'class_id' => $validated['class_id'],
            'user_id'  => auth()->id(),
            'content'  => $validated['content'],
            'links'    => $cleanLinks,
            'files'    => $uploadedFiles,
        ]);

        return redirect()->route('teacher.kelas.show', [
            'kelas' => $validated['class_id'],
            'tab'   => 'beranda'
        ])->with('success', 'Pengumuman berhasil diposting.');
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        // Validasi Kepemilikan (Hanya pembuat / guru kelas yang boleh edit)
        if ($announcement->user_id !== auth()->id() && $announcement->classRoom->teacher_id !== auth()->id()) {
            abort(403, 'Akses Ditolak');
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $announcement->update([
            'content' => $validated['content']
        ]);

        return back()->with('success', 'Pengumuman berhasil diperbarui.')->with('tab', 'beranda');
    }
    
    public function destroy(Announcement $announcement)
    {
        if ($announcement->classRoom->teacher_id !== auth()->id()) {
            abort(403, 'Akses Ditolak');
        }

        $files = $announcement->files ?? [];
        foreach ($files as $file) {
            if (isset($file['path'])) {
                Storage::disk('public')->delete($file['path']);
            }
        }

        $announcement->delete();

        return back()
            ->with('success', 'Pengumuman berhasil dihapus.')
            ->with('tab', 'beranda');
    }
}