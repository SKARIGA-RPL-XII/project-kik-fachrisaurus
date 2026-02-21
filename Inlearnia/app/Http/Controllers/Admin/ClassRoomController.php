<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query dengan relasi
        $query = ClassRoom::where('school_id', auth()->user()->school_id)
            ->with(['subject', 'teacher'])
            ->withCount(['students', 'meetings']); // Pastikan announcements juga dihitung

        // 2. Logika Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('teacher', function ($t) use ($search) {
                        $t->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // 3. Logika Sorting
        if ($request->filled('sort')) {
            if ($request->sort == 'terbaru') {
                $query->latest();
            } elseif ($request->sort == 'terlama') {
                $query->oldest();
            } elseif ($request->sort == 'siswa') {
                $query->orderBy('students_count', 'desc');
            }
        } else {
            // Default sort
            $query->latest();
        }

        $classes = $query->get();

        return view('admin.kelas.index', compact('classes'));
    }

    public function create()
    {
        $subjects = Subject::where('school_id', Auth::user()->school_id)->get();
        $teachers = User::where('school_id', Auth::user()->school_id)->where('role', 'teacher')->get();
        $students = User::where('school_id', Auth::user()->school_id)->where('role', 'student')->get();

        return view('admin.kelas.create', compact('subjects', 'teachers', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:users,id'],
            'students' => ['nullable', 'array'],
            'students.*' => ['exists:users,id'],
        ]);

        $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('classes', 'public') : null;

        $class = ClassRoom::create([
            'school_id' => Auth::user()->school_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logoPath,
        ]);

        if ($request->filled('students')) {
            $class->students()->sync($request->students);
        }

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(ClassRoom $kela) // Pastikan nama parameter route binding sesuai (kela/classRoom)
    {
        if ($kela->school_id != Auth::user()->school_id)
            abort(403);

        $subjects = Subject::where('school_id', Auth::user()->school_id)->get();
        $teachers = User::where('school_id', Auth::user()->school_id)->where('role', 'teacher')->get();
        $students = User::where('school_id', Auth::user()->school_id)->where('role', 'student')->get();
        $selectedStudents = $kela->students()->pluck('users.id')->toArray();

        return view('admin.kelas.edit', compact('kela', 'subjects', 'teachers', 'students', 'selectedStudents'));
    }

    public function update(Request $request, ClassRoom $kela)
    {
        if ($kela->school_id != Auth::user()->school_id)
            abort(403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:users,id'],
            'students' => ['nullable', 'array'],
            'students.*' => ['exists:users,id'],
        ]);

        $data = [
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('classes', 'public');
        }

        $kela->update($data);
        $kela->students()->sync($request->students ?? []);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(ClassRoom $kela)
    {
        if ($kela->school_id != Auth::user()->school_id)
            abort(403);
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }

    public function show(ClassRoom $kela)
    {
        if ($kela->school_id != Auth::user()->school_id)
            abort(403);

        $kela->load(['subject', 'teacher'])
            ->loadCount(['students', 'meetings', 'announcements']);

        // Feed beranda
        $announcements = $kela->announcements()->with('user')->get()
            ->map(fn($a) => $a->setAttribute('feed_type', 'announcement'));

        $allMeetings = $kela->meetings()->latest()->get()
            ->map(fn($m) => $m->setAttribute('feed_type', 'meeting'));

        $feeds = $announcements->concat($allMeetings)->sortByDesc('created_at')->values();

        // Tab pertemuan — terapkan filter dari request
        $tabMeetings = $kela->meetings()->latest()->get();

        if (request()->filled('type_filter')) {
            $tabMeetings = $tabMeetings->where('type', request('type_filter'));
        }

        if (request()->filled('topic_filter')) {
            $tabMeetings = $tabMeetings->where('topic', request('topic_filter'));
        }

        if (request()->filled('search')) {
            $search = strtolower(request('search'));
            $tabMeetings = $tabMeetings->filter(
                fn($m) => str_contains(strtolower($m->title), $search)
                || str_contains(strtolower($m->description ?? ''), $search)
            );
        }

        // Tab anggota
        $studentsQuery = $kela->students()->getQuery()->select('users.*');
        if (request()->filled('search') && request('tab') === 'anggota') {
            $s = request('search');
            $studentsQuery->where(fn($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"));
        }
        $students = $studentsQuery->paginate(10);

        $kelas = $kela;

        return view('admin.kelas.show', compact('kelas', 'feeds', 'tabMeetings', 'students'));
    }

}