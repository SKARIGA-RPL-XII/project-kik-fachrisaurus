<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\School;
use Illuminate\Support\Facades\DB;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    $request->validate([
        // data admin
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        'phone' => ['nullable', 'string', 'max:20'],

        // data sekolah
        'school_name' => ['required', 'string', 'max:255'],
        'school_address' => ['nullable', 'string'],
        'school_logo' => ['nullable', 'image', 'max:2048'],
    ]);

    $user = \Illuminate\Support\Facades\DB::transaction(function () use ($request) {

        $logoPath = null;
        if ($request->hasFile('school_logo')) {
            $logoPath = $request->file('school_logo')->store('schools', 'public');
        }

        $school = \App\Models\School::create([
            'name' => $request->school_name,
            'address' => $request->school_address,
            'logo' => $logoPath,
        ]);

        return \App\Models\User::create([
            'school_id' => $school->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'admin',
        ]);
    });

    \Illuminate\Support\Facades\Auth::login($user);

    return redirect()->route('admin.dashboard');
}


}
