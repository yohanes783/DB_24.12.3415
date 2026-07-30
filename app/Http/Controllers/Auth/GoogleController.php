<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect pengguna ke halaman login Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback respon dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 1. Cari user berdasarkan google_id TERLEBIH DAHULU
            $user = User::where('google_id', $googleUser->id)->first();

            // 2. Jika tidak ketemu via google_id, cari berdasarkan email
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Hubungkan google_id & avatar ke user yang sudah ada (TANPA MENGUBAH ROLE)
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar'    => $googleUser->avatar ?? $user->avatar,
                    ]);
                } else {
                    // Jika pengguna benar-benar baru, buatkan akun baru (default role: user)
                    $user = User::create([
                        'name'              => $googleUser->name,
                        'email'             => $googleUser->email,
                        'google_id'         => $googleUser->id,
                        'avatar'            => $googleUser->avatar,
                        'password'          => Hash::make(Str::random(16)),
                        'email_verified_at' => now(),
                        'role'              => 'user',
                    ]);
                }
            }

            // Regenerate session sebelum login untuk keamanan dari Session Hijacking
            request()->session()->regenerate();

            // Login-kan user ke sistem Laravel
            Auth::login($user);

            // -----------------------------------------------------------
            // LOGIKA AUTO-REDIRECT BERDASARKAN ROLE & STATUS PARTNER
            // -----------------------------------------------------------

            // 1. Jika Superadmin / Admin
            if ($user->role === 'superadmin' || $user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang kembali, Admin!');
            }

            // 2. Jika Partner atau memiliki data pengajuan Partner
            if ($user->role === 'partner' || $user->partner) {
                if ($user->partner && $user->partner->status === 'approved') {
                    return redirect()->route('partner.dashboard')
                        ->with('success', 'Berhasil login ke Dashboard Partner!');
                }

                // Jika statusnya masih 'pending' atau 'rejected'
                return redirect()->route('partner.pending')
                    ->with('info', 'Status pengajuan partner kamu masih dalam proses peninjauan.');
            }

            // 3. Jika User biasa / Pembeli tiket
            return redirect()->intended(route('home'))
                ->with('success', 'Berhasil login via Google!');

        } catch (Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());

            return redirect()->route('login')->with('error', 'Gagal melakukan login via Google, silakan coba lagi.');
        }
    }
}
