@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto my-10 p-6 sm:p-8 bg-white rounded-2xl shadow-sm border border-slate-100">
    <h2 class="text-2xl font-bold text-slate-800 mb-1">Daftar Sebagai Penyelenggara Event (Partner / HIMA)</h2>
    <p class="text-slate-500 mb-6 text-sm">Isi formulir di bawah ini untuk mengajukan organisasi/kepanitiaan kamu.</p>

    {{-- Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl text-red-700 text-sm">
            <p class="font-bold mb-1">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('partner.register.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- JIKA PENDAFTAR BELUM LOGIN (Input Akun Baru) --}}
        @guest
            <div class="p-5 mb-6 bg-slate-50 border border-slate-200/80 rounded-xl">
                <h3 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-wider">1. Informasi Akun Penanggung Jawab (PIC)</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1 text-sm">Nama Ketua / PIC</label>
                        <input type="text" name="user_name" value="{{ old('user_name') }}" placeholder="Contoh: Ahmad Subagja" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" required>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 mb-1 text-sm">Email Aktif</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@domain.com" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1 text-sm">Password</label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" required>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1 text-sm">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" required>
                        </div>
                    </div>
                </div>
            </div>
        @endguest

        {{-- INFORMASI ORGANISASI / PARTNER --}}
        <div class="p-5 mb-6 bg-slate-50/50 border border-slate-200/80 rounded-xl">
            <h3 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-wider">
                @auth 1. Informasi Profil Partner @else 2. Informasi Organisasi / Partner @endauth
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block font-medium text-slate-700 mb-1 text-sm">Nama Organisasi / HIMA / Komunitas</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: HIMA Informatika Amikom" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" required>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1 text-sm">Logo Organisasi (Opsional)</label>
                    <input type="file" name="logo" accept="image/*" class="w-full p-2 border border-slate-200 rounded-xl text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1 text-sm">Deskripsi Singkat Organisasi</label>
                    <textarea name="description" rows="4" placeholder="Jelaskan mengenai organisasi atau event yang biasa kamu selenggarakan..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" required>{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/20 transition duration-200">
            Kirim Pengajuan Partner
        </button>

        @guest
            <p class="text-center text-sm text-slate-500 mt-5">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk di sini</a> sebelum mengajukan.
            </p>
        @endguest
    </form>
</div>
@endsection