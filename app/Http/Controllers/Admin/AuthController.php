<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman formulir login portal internal (Superadmin, Admin, & Partner).
     */
    public function showLogin() {
        return view('admin.auth.login');
    }

    /**
     * Memproses autentikasi submit login dan mengarahkan sesuai Role.
     */
    public function login(Request $request) {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 1. Coba Log in menggunakan email & password
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 2. Arahkan pengguna ke dashboard masing-masing berdasarkan role
            switch ($user->role) {
                case 'superadmin':
                case 'admin':
                    // Keduanya diarahkan ke halaman dashboard admin yang sama
                    $request->session()->regenerate();
                    return redirect()->route('admin.dashboard');

                case 'partner':
                    // Cek status persetujuan partner
                    if ($user->partner && $user->partner->status === 'approved') {
                        $request->session()->regenerate();
                        return redirect()->route('partner.dashboard');
                    }

                    // Jika partner masih pending atau belum disetujui superadmin
                    $request->session()->regenerate();
                    return redirect()->route('partner.pending');

                default:
                    // Jika role-nya hanya 'user' biasa, keluarkan dan tolak akses
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akses ditolak! Halaman ini khusus untuk Pengelola Platform / Partner.',
                    ]);
            }
        }

        // Jika kredensial email/password salah
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan tidak sesuai.',
        ]);
    }

    /**
     * Memproses Logout.
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect kembali ke halaman login portal
        return redirect()->route('admin.login');
    }
}
