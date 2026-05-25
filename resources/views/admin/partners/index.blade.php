@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Bagian Atas: Tombol Tambah Data -->
    <div class="flex justify-end items-center">
        <a href="/admin/partners/create" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            + Tambah Partner Baru
        </a>
    </div>
    
<!-- Form Input Search Partner -->
<div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex flex-col sm:flex-row gap-3 justify-between items-center mb-4">
    <form action="/admin/partners" method="GET" class="w-full sm:max-w-md relative">
        <input type="text" name="search" value="{{ $search ?? request('search') }}"
               placeholder="Cari partner berdasarkan nama..."
               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
        <div class="absolute left-3 top-3 text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </form>
    @if(request('search'))
        <a href="/admin/partners" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">Reset Pencarian</a>
    @endif
</div>

    <!-- Wadah Utama Tabel -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                        <th class="py-4 px-6 text-center w-16">NO</th>
                        <th class="py-4 px-6 w-32">LOGO</th>
                        <th class="py-4 px-6">NAMA PARTNER</th>
                        <th class="py-4 px-6">URL LOGO</th>
                        <th class="py-4 px-6 w-40">DI BUAT</th>
                        <th class="py-4 px-6 text-center w-32">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @forelse($partners as $index => $partner)
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- Nomor -->
                            <td class="py-4 px-6 text-center text-slate-400 font-normal">
                                {{ $index + 1 }}
                            </td>

                            <!-- Logo -->
                            <td class="py-4 px-6">
                                <div class="w-16 h-16 bg-white border border-slate-100 rounded-xl p-2 flex items-center justify-center">
                                    <img src="{{ $partner->logo_url }}" class="max-w-full max-h-full object-contain rounded-lg" alt="Logo">
                                </div>
                            </td>

                            <!-- Nama Partner -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $partner->name }}</span>
                                    <span class="text-[11px] text-slate-400 font-normal mt-0.5">Partner Event</span>
                                </div>
                            </td>

                            <!-- URL Logo -->
                            <td class="py-4 px-6">
                                <a href="{{ $partner->logo_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 break-all max-w-xs block font-normal text-xs underline">
                                    {{ $partner->logo_url }}
                                </a>
                            </td>

                            <!-- Tanggal Dibuat -->
                            <td class="py-4 px-6 text-slate-500 text-xs font-normal">
                                {{ $partner->created_at ? $partner->created_at->format('d M Y') : '-' }}
                            </td>

                            <!-- Tombol Aksi -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="/admin/partners/{{ $partner->id }}/edit" class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Form Hapus -->
                                    <form action="/admin/partners/{{ $partner->id }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400 font-medium">
                                Belum ada data partner yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
