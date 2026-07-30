<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartnerRegisterController extends Controller
{
    public function create()
    {
        // 1. Jika User Sudah Login, Cek Status Pendaftarannya
        if (Auth::check()) {
            $partner = Auth::user()->partner;

            if ($partner) {
                // Jika status masih pending, lempar langsung ke halaman pending
                if ($partner->status === 'pending') {
                    return redirect()->route('partner.pending');
                }

                // Jika sudah disetujui, lempar langsung ke Dashboard Partner
                if ($partner->status === 'approved') {
                    return redirect()->route('partner.dashboard');
                }
            }
        }

        // 2. Jika Belum Login ATAU Belum Pernah Daftar (atau pernah rejected), Tampilkan Form
        return view('partner.register');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        // Validasi tambahan jika pendaftar belum login (buat akun baru)
        if (!Auth::check()) {
            $rules['user_name'] = 'required|string|max:255';
            $rules['email']     = 'required|string|email|max:255|unique:users,email';
            $rules['password']  = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Ambil user aktif atau buat user baru
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = User::create([
                'name'     => $request->user_name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'partner',
            ]);

            Auth::login($user);
        }

        // Hapus data/logo lama jika pendaftaran sebelumnya ditolak (rejected)
        if ($user->partner && $user->partner->status === 'rejected') {
            if ($user->partner->logo && Storage::disk('public')->exists($user->partner->logo)) {
                Storage::disk('public')->delete($user->partner->logo);
            }
            $user->partner()->delete();
        }

        // Upload Logo Baru jika ada
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partners/logos', 'public');
        }

        // Simpan Data Partner
        Partner::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'description' => $request->description,
                'logo'        => $logoPath ?? $user->partner?->logo,
                'status'      => 'pending',
            ]
        );

        // REDIRECT LANGSUNG KE HALAMAN PENDING (Bukan 'welcome')
        return redirect()->route('partner.pending')->with('success', 'Pengajuan partner berhasil dikirim!');
    }
}