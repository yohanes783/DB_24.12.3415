<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PartnerAuthController extends Controller
{
    // Menampilkan halaman form pendaftaran
    public function showRegister()
    {
        return view('partner.register');
    }

    // Proses simpan data pendaftaran
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'organization'  => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'phone'         => 'required|string|max:20',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat User dengan role 'partner'
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'partner',
            ]);

            // 2. Buat Data Partner terkait dengan status 'pending'
            Partner::create([
                'user_id'     => $user->id,
                'name'        => $request->organization,
                'phone'       => $request->phone,
                'status'      => 'pending',
            ]);
        });

        return redirect()->route('partner.pending')->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan Superadmin.');
    }

    // Halaman pemberitahuan akun ditinjau / pending
    public function pending()
    {
        return view('partner.pending');
    }
}