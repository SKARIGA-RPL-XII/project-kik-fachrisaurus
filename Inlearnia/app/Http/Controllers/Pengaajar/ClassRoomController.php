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
    public function index()
{
    $classes = ClassRoom::where('school_id', auth()->user()->school_id)
        ->with(['subject', 'teacher'])
        ->withCount(['students', 'meetings'])
        ->latest()
        ->get();

    return view('admin.kelas.index', compact('classes'));
}


    public function create()
    {
        $subjects = Subject::where('school_id', Auth::user()->school_id)->get();

        $teachers = User::where('school_id', Auth::user()->school_id)
            ->where('role', 'teacher')
            ->get();

        $students = User::where('school_id', Auth::user()->school_id)
            ->where('role', 'student')
            ->get();

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

        // upload logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('classes', 'public');
        }

        $class = ClassRoom::create([
            'school_id' => Auth::user()->school_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logoPath,
        ]);

        // attach students (optional)
        if ($request->filled('students')) {
            $class->students()->sync($request->students);
        }

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(ClassRoom $kela)
    {
        if ($kela->school_id != Auth::user()->school_id) {
            abort(403);
        }

        $subjects = Subject::where('school_id', Auth::user()->school_id)->get();

        $teachers = User::where('school_id', Auth::user()->school_id)
            ->where('role', 'teacher')
            ->get();

        $students = User::where('school_id', Auth::user()->school_id)
            ->where('role', 'student')
            ->get();

        $selectedStudents = $kela->students()->pluck('users.id')->toArray();

        return view('admin.kelas.edit', compact('kela', 'subjects', 'teachers', 'students', 'selectedStudents'));
    }

    public function update(Request $request, ClassRoom $kela)
    {
        if ($kela->school_id != Auth::user()->school_id) {
            abort(403);
        }

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

        // update students
        $kela->students()->sync($request->students ?? []);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(ClassRoom $kela)
    {
        if ($kela->school_id != Auth::user()->school_id) {
            abort(403);
        }

        $kela->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}
