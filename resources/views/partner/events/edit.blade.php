@extends('layouts.partner')

@section('page_title', 'Edit Event')
@section('page_subtitle', 'Perbarui detail event ' . $event->title)

@section('content')
<div class="max-w-3xl bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
    <form action="{{ route('partner.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Judul Event --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Judul Event</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        {{-- Kategori & Poster --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (old('category_id', $event->category_id) == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Ganti Poster (Opsional)</label>
                <input type="file" name="poster" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm">
                
                {{-- Preview Poster Lama --}}
                @if($event->poster_path)
                    <div class="mt-2 flex items-center gap-3">
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster Saat Ini" class="w-12 h-12 object-cover rounded-lg border">
                        <span class="text-xs text-slate-400">Poster saat ini</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tanggal & Waktu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pelaksanaan</label>
                <input type="date" name="date" value="{{ old('date', $event->date) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Pelaksanaan</label>
                <div class="grid grid-cols-2 gap-3">
                    @php
                        // Memisahkan string waktu "08:00 - 12:00 WIB" menjadi jam mulai & selesai
                        $timeParts = explode(' - ', $event->time ?? '');
                        $startTime = isset($timeParts[0]) ? trim($timeParts[0]) : '';
                        $endTime   = isset($timeParts[1]) ? trim(str_replace('WIB', '', $timeParts[1])) : '';
                    @endphp
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Mulai</label>
                        <input type="time" name="start_time" value="{{ old('start_time', $startTime) }}" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Selesai</label>
                        <input type="time" name="end_time" value="{{ old('end_time', $endTime) }}" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    </div>
                </div>
            </div>
        </div>

        {{-- Harga & Kuota --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Harga Tiket (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $event->price) }}" required min="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Kuota Tiket</label>
                <input type="number" name="stock" value="{{ old('stock', $event->stock ?? $event->quota) }}" required min="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        {{-- Lokasi --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi / Venue</label>
            <input type="text" name="location" value="{{ old('location', $event->location) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Event</label>
            <textarea name="description" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description', $event->description) }}</textarea>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-4 pt-4">
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('partner.events.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection