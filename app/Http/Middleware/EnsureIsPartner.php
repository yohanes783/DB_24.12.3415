<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsPartner
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $partner = $user->partner; // Mengambil relasi partner dari user yang sedang login

        // 2. Jika user MEMILIKI pengajuan partner
        if ($partner) {
            // Jika sudah di-approve oleh Admin
            if ($partner->status === 'approved') {
                return $next($request);
            }

            // Jika status masih pending
            if ($partner->status === 'pending') {
                return redirect()->route('partner.pending')
                    ->with('warning', 'Pendaftaran HIMA/Organisasi Anda masih menunggu persetujuan Superadmin.');
            }

            // Jika status ditolak
            if ($partner->status === 'rejected' || $partner->status === 'ditolak') {
                return redirect()->route('partner.rejected')
                    ->with('error', 'Pendaftaran Anda ditolak. Silakan hubungi admin platform.');
            }
        }

        // 3. Jika role-nya sudah 'partner' tapi data partner belum ada di DB
        if ($user->isPartner() && !$partner) {
            return redirect()->route('home')->with('error', 'Profil Partner tidak ditemukan.');
        }

        // 4. Jika user biasa yang belum pernah mendaftar partner sama sekali
        abort(403, 'Akses khusus Penyelenggara Event (Partner). Silakan daftar sebagai partner terlebih dahulu.');
    }
}
