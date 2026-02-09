<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        // ===== RATE LIMIT 60 DETIK =====
        $lastOtp = EmailOtp::where('email', $request->email)
            ->latest()
            ->first();

        if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
            return response()->json([
                'message' => 'Tunggu 60 detik sebelum kirim OTP lagi'
            ], 429);
        }

        $otp = rand(1000, 9999);

        EmailOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5),
        ]);

        Mail::raw(
            "Kode OTP pendaftaran kamu: $otp\n\nBerlaku selama 5 menit.",
            function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Kode OTP Pendaftaran');
            }
        );

        return response()->json([
            'message' => 'OTP berhasil dikirim'
        ]);
    }
}
