<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = User::query()
            ->where('school_id', $user->school_id)
            ->where('id', '!=', $user->id);

        $query->when($request->search, function ($q, $search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
            });
        });

        $query->when($request->role, fn($q, $role) => $q->where('role', $role));

        $schoolOwnerId = User::where('school_id', $user->school_id)
            ->where('role', 'admin')
            ->oldest('id')
            ->value('id');

        $users = $query->latest()->paginate(8)->withQueryString();

        return view('admin.users.index', compact('users', 'schoolOwnerId'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users'],
            'role'          => ['required', 'in:admin,teacher,student'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'password'      => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $this->handleImageUpload($request);
        }

        $validated['password']  = Hash::make($validated['password']);
        $validated['school_id'] = auth()->user()->school_id;

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $this->ensureUserBelongsToSchool($user);

        // Logic pengambilan data kelas berdasarkan role
        $classes = match($user->role) {
            'teacher' => $user->teachingClasses()->latest()->get(),
            'student' => $user->classes()->latest()->get(),
            default   => collect(),
        };

        $enrolledClassCount = $classes->count();

        return view('admin.users.show', compact('user', 'classes', 'enrolledClassCount'));
    }

    public function edit(User $user)
    {
        $this->ensureUserBelongsToSchool($user);
        $this->ensureCanManageUser($user);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensureUserBelongsToSchool($user);
        $this->ensureCanManageUser($user);

        $validated = $request->validate([
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'          => ['required', 'in:admin,teacher,student'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'password'      => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) Storage::disk('public')->delete($user->profile_photo);
            $validated['profile_photo'] = $this->handleImageUpload($request);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->ensureUserBelongsToSchool($user);
        $this->ensureCanManageUser($user);

        if ($user->profile_photo) Storage::disk('public')->delete($user->profile_photo);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    /* PRIVATE HELPERS */

    private function handleImageUpload(Request $request)
    {
        return $request->file('profile_photo')->store('profiles', 'public');
    }

    private function ensureUserBelongsToSchool(User $user)
    {
        if ($user->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function ensureCanManageUser(User $targetUser)
    {
        if ($targetUser->role !== 'admin') return;

        $schoolOwnerId = User::where('school_id', auth()->user()->school_id)
            ->where('role', 'admin')
            ->oldest('id')
            ->value('id');

        if (auth()->id() !== $schoolOwnerId) {
            abort(403, 'Hanya Admin Utama yang dapat mengelola Admin lain.');
        }
    }
}