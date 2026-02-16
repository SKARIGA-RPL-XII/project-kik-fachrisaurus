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
        // Query: Hanya kelas dimana user ini adalah pengajarnya
        $query = ClassRoom::where('school_id', Auth::user()->school_id)
            ->where('teacher_id', Auth::id()) // FILTER PENTING: Hanya kelas dia sendiri
            ->with(['subject']) // Tidak perlu load teacher lagi karena sudah pasti dia
            ->withCount(['students', 'meetings', 'announcements']);

        // Logika Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Logika Sorting
        if ($request->filled('sort')) {
            if ($request->sort == 'terbaru') $query->latest();
            elseif ($request->sort == 'terlama') $query->oldest();
            elseif ($request->sort == 'siswa') $query->orderBy('students_count', 'desc');
        } else {
            $query->latest();
        }

        $classes = $query->get();

        return view('pengajar.kelas.index', compact('classes'));
    }

    public function show($id)
    {
        // 1. Ambil Data Kelas dengan Relasi Lengkap
        // Menggunakan where 'id' dan 'teacher_id' sekaligus untuk keamanan (agar pengajar lain tidak bisa akses via URL)
        $kelas = ClassRoom::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->with([
                'subject',
                'teacher',
                'students',
                // Load Announcement urutkan dari yang terbaru
                'announcements' => function($q) {
                    $q->latest();
                },
                // Load Meetings urutkan dari yang terbaru
                'meetings' => function($q) {
                    $q->latest();
                }
            ])
            ->withCount(['students', 'meetings', 'announcements'])
            ->firstOrFail(); // Akan return 404 jika ID salah atau bukan milik pengajar tersebut

        // 2. Logika Mapping Data untuk Tab "Beranda" (Feed Campuran)
        
        // Mapping Pengumuman
        $announcements = $kelas->announcements->map(function($item) {
            // Kita set properti 'type' secara manual agar di View bisa dicek @if($item->type == 'announcement')
            $item->type = 'announcement'; 
            $item->date_sort = $item->created_at;
            return $item;
        });

        // Mapping Pertemuan
        $meetings = $kelas->meetings->map(function($item) {
            // Backup tipe asli (misal: 'tugas' atau 'materi') ke variabel baru 'type_meeting'
            // karena variabel 'type' akan kita pakai untuk logika Feed
            $item->type_meeting = $item->type; 
            
            // Set type utama jadi 'meeting' untuk logika View Beranda
            $item->type = 'meeting'; 
            $item->date_sort = $item->created_at;
            return $item;
        });

        // 3. Gabungkan Collection dan Sort berdasarkan tanggal terbaru (date_sort)
        $feeds = $announcements->concat($meetings)->sortByDesc('date_sort');

        return view('pengajar.kelas.show', compact('kelas', 'feeds'));
    }
}