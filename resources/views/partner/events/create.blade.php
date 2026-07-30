@extends('layouts.partner')

@section('page_title', 'Buat Event Baru')
@section('page_subtitle', 'Isi informasi lengkap event yang ingin kamu selenggarakan')

@section('content')
<div class="max-w-3xl bg-white p-8 rounded-2xl shadow-sm border border-slate-100">

    {{-- Alert jika ada error validasi --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-sm font-bold text-red-600 mb-2">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside text-sm text-red-500">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('partner.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Judul Event</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Contoh: Amikom Tech Seminar 2026">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Poster Event</label>
                {{-- Disesuaikan name="poster" agar cocok dengan controller --}}
                <input type="file" name="poster" required accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pelaksanaan</label>
                <input type="date" name="date" value="{{ old('date') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs text-slate-500 mb-1">Mulai</label>
                <input type="time" name="start_time" value="{{ old('start_time', isset($event) ? Str::before($event->time, ' - ') : '') }}" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs text-slate-500 mb-1">Selesai</label>
                <input type="time" name="end_time" value="{{ old('end_time', isset($event) ? Str::after($event->time, ' - ') : '') }}" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 outline-none text-sm">
            </div>
        </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Harga Tiket (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" required min="0" placeholder="Isi 0 jika Gratis" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Kuota Tiket</label>
                {{-- Disesuaikan name="stock" agar cocok dengan controller --}}
                <input type="number" name="stock" value="{{ old('stock') }}" required min="1" placeholder="Jumlah kuota tiket" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi / Venue</label>
            <input type="text" name="location" value="{{ old('location') }}" required placeholder="Contoh: Ruang Cinema Amikom / Zoom Meeting" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Event</label>
            <textarea name="description" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Tuliskan detail event...">{{ old('description') }}</textarea>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition">
                Simpan & Publikasikan
            </button>
            <a href="{{ route('partner.events.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection