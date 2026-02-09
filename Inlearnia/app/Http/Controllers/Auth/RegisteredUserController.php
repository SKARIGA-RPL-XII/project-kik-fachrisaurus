<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\EmailOtp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    // ================= SEND OTP =================
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $otp = rand(1000, 9999);

        EmailOtp::where('email', $request->email)->delete();

        EmailOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5),
        ]);

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json([
            'message' => 'OTP berhasil dikirim'
        ]);
    }

    // ================= REGISTER =================
    public function store(Request $request)
    {
        $request->validate([
            // admin
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'otp' => ['required', 'digits:4'],
            'password' => ['required', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],

            // sekolah
            'school_name' => ['required', 'string'],
            'school_address' => ['nullable', 'string'],
            'school_logo' => ['nullable', 'image', 'max:2048'],
        ]);

        // ===== CEK OTP =====
        $otp = EmailOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expired_at', '>', now())
            ->first();

        if (!$otp) {
            return back()->withErrors([
                'otp' => 'OTP salah atau sudah kadaluarsa'
            ]);
        }

        $user = DB::transaction(function () use ($request) {

            $logoPath = null;
            if ($request->hasFile('school_logo')) {
                $logoPath = $request->file('school_logo')
                    ->store('schools', 'public');
            }

            $school = School::create([
                'name' => $request->school_name,
                'address' => $request->school_address,
                'logo' => $logoPath,
            ]);

            return User::create([
                'school_id' => $school->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);
        });

        EmailOtp::where('email', $request->email)->delete();

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }
}
