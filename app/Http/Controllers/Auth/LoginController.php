<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba proses login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 3. Admin & Superadmin langsung ke Dashboard Admin
            if ($user->role === 'superadmin' || $user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // 4. Jika Partner berstatus PENDING, cegat dan arahkan ke Halaman Pending
            if ($user->partner && $user->partner->status === 'pending') {
                return redirect()->route('partner.pending');
            }

            // 5. Untuk Partner (APPROVED) maupun User Biasa:
            // Arahkan ke halaman utama (home) agar bisa langsung pesan tiket.
            // (Partner dapat masuk ke dashboard via tombol di Navbar)
            return redirect()->intended(route('home'));
        }

        // 6. Jika gagal, kembalikan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}