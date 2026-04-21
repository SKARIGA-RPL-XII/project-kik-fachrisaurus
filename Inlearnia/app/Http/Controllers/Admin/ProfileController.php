<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $school = $user->school;

        $teacherCount = User::where('school_id', $user->school_id)->where('role', 'teacher')->count();
        $studentCount = User::where('school_id', $user->school_id)->where('role', 'student')->count();
        $classCount = ClassRoom::where('school_id', $user->school_id)->count();

        return view('admin.profile.index', compact(
            'user', 'school', 'teacherCount', 'studentCount', 'classCount',
        ));
    }

    public function indexPengajar()
    {
        $user = Auth::user();
        $school = $user->school;

        return view('pengajar.profile.index', compact('user', 'school'));
    }

    public function indexSiswa()
    {
        $user = Auth::user();
        $school = $user->school;

        return view('siswa.profile.index', compact('user', 'school'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'         => ['nullable', 'string', 'max:20'],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->save();

        $route = match($user->role) {
            'teacher' => 'teacher.profile.index',
            'student' => 'student.profile.index',
            default   => 'admin.profile.index',
        };

        return redirect()->route($route)->with('success', 'Profil berhasil diperbarui.');
    }

    public function editSchool()
    {
        $school = Auth::user()->school;

        return view('admin.profile.edit-school', compact('school'));
    }

    public function updateSchool(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'npsn'           => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string'],
            'logo'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'principal_name' => ['nullable', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:255'],
            'level'          => ['nullable', 'string', 'max:50'],
            'status'         => ['nullable', 'string', 'max:50'],
            'accreditation'  => ['nullable', 'string', 'max:5'],
        ]);

        $user   = Auth::user();
        $school = School::firstOrNew(['id' => $user->school_id]);
        $school->fill($request->except('logo'));

        if ($request->hasFile('logo')) {
            if ($school->logo) {
                Storage::disk('public')->delete($school->logo);
            }
            $school->logo = $request->file('logo')->store('school-logos', 'public');
        }

        $school->save();

        // Pastikan user terhubung ke school ini
        if (!$user->school_id) {
            $user->school_id = $school->id;
            $user->save();
        }

        return redirect()->route('admin.profile.index')->with('success', 'Data sekolah diperbarui.');
    }
}