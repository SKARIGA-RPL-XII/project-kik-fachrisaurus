<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil kelas berdasarkan relasi Many-to-Many pada tabel pivot siswa
        $classes = ClassRoom::where('school_id', Auth::user()->school_id)
            ->whereHas('students', function ($query) {
                // Asumsi relasi 'students' terhubung ke tabel users dengan ID user yang sedang login
                $query->where('users.id', Auth::id()); 
            })
            ->with(['subject', 'teacher']) // Eager load teacher agar namanya bisa ditampilkan
            ->withCount(['students', 'meetings', 'announcements'])
            ->when($request->search, fn($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($request->sort, function ($q, $sort) {
                match ($sort) {
                    'terlama' => $q->oldest(),
                    'siswa'   => $q->orderByDesc('students_count'),
                    default   => $q->latest(),
                };
            }, fn($q) => $q->latest())
            ->get();

        return view('siswa.kelas.index', compact('classes'));
    }

    public function show(Request $request, $id)
    {
        // Pastikan siswa hanya bisa melihat detail kelas jika dia terdaftar di dalamnya
        $kelas = ClassRoom::where('id', $id)
            ->whereHas('students', function ($query) {
                $query->where('users.id', Auth::id());
            })
            ->with([
                'subject',
                'teacher',
                'announcements' => fn($q) => $q->latest(),
                'meetings'      => fn($q) => $q->latest()
            ])
            ->withCount(['students', 'meetings', 'announcements'])
            ->firstOrFail();

        $tabMeetings = $kelas->meetings()
            ->when($request->search, fn($q, $search) => $q->where(fn($sub) => $sub->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%")))
            ->when($request->type_filter, fn($q, $type) => $q->where('type', $type))
            ->when($request->topic_filter, fn($q, $topic) => $q->where('topic', $topic))
            ->latest()
            ->get();

        $students = $kelas->students()
            ->when($request->search, fn($q, $search) => $q->where(fn($sub) => $sub->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")))
            ->paginate(10)
            ->withQueryString();

        $announcements = $kelas->announcements->map(fn($item) => tap($item, fn($i) => $i->feed_type = 'announcement'));
        $meetingsForFeed = $kelas->meetings->map(fn($item) => tap($item, fn($i) => $i->feed_type = 'meeting'));

        $feeds = $announcements->concat($meetingsForFeed)
            ->sortByDesc('created_at')
            ->values();

        // Mengembalikan view khusus siswa (pastikan file view siswa.kelas.show sudah Anda buat)
        return view('siswa.kelas.show', compact('kelas', 'feeds', 'tabMeetings', 'students'));
    }

    // Catatan: Fungsi removeStudent dihapus karena role siswa tidak boleh mengeluarkan siswa lain.
}