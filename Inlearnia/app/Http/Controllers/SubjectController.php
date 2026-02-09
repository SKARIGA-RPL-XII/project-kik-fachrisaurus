<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('school_id', Auth::user()->school_id)
            ->latest()
            ->get();

        return view('admin.mapel.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'curriculum' => ['required', 'in:k13,merdeka'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subjects', 'public');
        }

        Subject::create([
            'school_id' => Auth::user()->school_id,
            'name' => $request->name,
            'curriculum' => $request->curriculum,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil ditambahkan!');
    }

    public function edit(Subject $subject)
    {
        // FIX: pastiin mapel milik sekolah admin yg login
        if ((int) $subject->school_id !== (int) Auth::user()->school_id) {
            abort(403, 'Mapel ini bukan milik sekolah kamu.');
        }

        return view('admin.mapel.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        if ((int) $subject->school_id !== (int) Auth::user()->school_id) {
            abort(403, 'Mapel ini bukan milik sekolah kamu.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'curriculum' => ['required', 'in:k13,merdeka'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'name' => $request->name,
            'curriculum' => $request->curriculum,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('subjects', 'public');
        }

        $subject->update($data);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil diupdate!');
    }

    public function destroy(Subject $subject)
    {
        if ((int) $subject->school_id !== (int) Auth::user()->school_id) {
            abort(403, 'Mapel ini bukan milik sekolah kamu.');
        }

        $subject->delete();

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil dihapus!');
    }
}
