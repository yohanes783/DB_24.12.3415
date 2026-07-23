<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str; // Tambahan import untuk class Str
use Exception;

class SocialiteController extends Controller
{
    // Mengarahkan pengguna ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani callback dari Google setelah login sukses
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari pengguna berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Jika user sudah ada, update google_id nya jika belum terisi
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            } else {
                // Jika user belum ada, buat user baru (Registrasi otomatis)
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)) // Menggunakan Str::random yang aman
                ]);
            }

            Auth::login($user);

            // Sesuaikan '/dashboard' dengan rute halaman utama admin Anda setelah berhasil login
            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            // Mengarahkan kembali ke route login admin jika gagal
            return redirect()->route('admin.login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
