@extends('layouts.app')
@section('title', 'Status Pengajuan Partner')

@section('content')
<main class="max-w-2xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-10 shadow-sm">
        
        @php
            $status = auth()->user()->partner->status ?? 'pending';
        @endphp

        {{-- 1. JIKA STATUS DITOLAK (REJECTED) --}}
        @if ($status === 'rejected')
            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">
                ❌
            </div>
            <h1 class="text-2xl font-black text-slate-800 mb-3">
                Pengajuan Partner Ditolak
            </h1>
            <p class="text-slate-500 leading-relaxed mb-8">
                Mohon maaf, pengajuan organisasi <strong class="text-red-600">{{ auth()->user()->partner->name }}</strong> belum dapat disetujui oleh Superadmin saat ini. Silakan daftar ulang dengan melengkapi data yang valid.
            </p>
            <div class="flex items-center justify-center gap-4 flex-wrap">
                <a href="{{ route('partner.register') }}" 
                   class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all active:scale-95">
                    Daftar Ulang Partner
                </a>
                <a href="{{ route('home') }}" 
                   class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-all">
                    Kembali ke Beranda
                </a>
            </div>

        {{-- 2. JIKA STATUS DISETUJUI (APPROVED) --}}
        @elseif ($status === 'approved')
            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">
                🎉
            </div>
            <h1 class="text-2xl font-black text-slate-800 mb-3">
                Pengajuan Selamat, Partner Disetujui!
            </h1>
            <p class="text-slate-500 leading-relaxed mb-8">
                Akun organisasi <strong class="text-emerald-600">{{ auth()->user()->partner->name }}</strong> telah aktif. Kamu sekarang bisa mulai membuat dan mengelola event.
            </p>
            <a href="{{ route('partner.dashboard') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-100 transition-all active:scale-95">
                Masuk ke Dashboard Partner
            </a>

        {{-- 3. JIKA STATUS MENUNGGU (PENDING) --}}
        @else
            <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">
                ⏳
            </div>
            <h1 class="text-2xl font-black text-slate-800 mb-3">
                Status: Menunggu Persetujuan (Pending)
            </h1>
            <p class="text-slate-500 leading-relaxed mb-8">
                Akun organisasi <strong class="text-indigo-600">{{ auth()->user()->partner->name ?? 'Anda' }}</strong> masih dalam tahap verifikasi oleh Superadmin.<br>
                Fitur pembuatan event akan otomatis aktif setelah pengajuan disetujui.
            </p>
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all active:scale-95">
                Kembali ke Halaman Utama
            </a>
        @endif

    </div>
</main>
@endsection