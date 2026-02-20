<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    public function index(Request $request)
    {
        $classes = ClassRoom::where('school_id', Auth::user()->school_id)
            ->where('teacher_id', Auth::id())
            ->with(['subject'])
            ->withCount(['students', 'meetings', 'announcements'])
            // Clean code: Gunakan when() untuk pencarian
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            // Clean code: Gunakan when() dan match() untuk sorting
            ->when($request->sort, function ($query, $sort) {
                match ($sort) {
                    'terlama' => $query->oldest(),
                    'siswa'   => $query->orderByDesc('students_count'),
                    default   => $query->latest(), // default 'terbaru'
                };
            }, function ($query) {
                $query->latest(); // Default jika tidak ada request sort
            })
            ->get();

        return view('pengajar.kelas.index', compact('classes'));
    }

    public function show(Request $request, $id)
    {
        // 1. Ambil Data Kelas Utama (beserta relasi untuk Feed Beranda)
        $kelas = ClassRoom::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->with([
                'subject',
                'teacher',
                'students',
                'announcements' => fn($q) => $q->latest(),
                'meetings'      => fn($q) => $q->latest() // Untuk Feed Beranda murni
            ])
            ->withCount(['students', 'meetings', 'announcements'])
            ->firstOrFail();

        // 2. Query Khusus Tab "Daftar Pertemuan" (Terapkan Filter)
        $tabMeetings = $kelas->meetings()
            // Pencarian Teks (Judul / Deskripsi)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            // Filter Jenis (Materi / Tugas)
            ->when($request->type_filter, function ($query, $type) {
                $query->where('type', $type);
            })
            // Filter Topik (BARU)
            ->when($request->topic_filter, function ($query, $topic) {
                $query->where('topic', $topic);
            })
            ->latest()
            ->get();


        // 3. Logika Mapping Data untuk Tab "Beranda" (Feed Campuran)
        // Gunakan atribut bantuan 'feed_type' agar tidak menimpa kolom 'type' bawaan meeting
        $announcements = $kelas->announcements->map(function ($item) {
            $item->feed_type = 'announcement';
            return $item;
        });

        $meetingsForFeed = $kelas->meetings->map(function ($item) {
            $item->feed_type = 'meeting';
            return $item;
        });

        // Gabungkan dan urutkan berdasarkan waktu pembuatan terbaru
        $feeds = $announcements->concat($meetingsForFeed)
            ->sortByDesc('created_at')
            ->values(); // Reset array keys setelah di-sort

        return view('pengajar.kelas.show', compact('kelas', 'feeds', 'tabMeetings'));
    }
}