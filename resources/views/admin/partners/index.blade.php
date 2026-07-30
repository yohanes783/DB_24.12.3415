@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Bagian Atas: Tombol Tambah Data & Filter Tabs -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Filter Status (Pending, Approved, Rejected) -->
        <div class="flex items-center gap-1 bg-slate-100 p-1.5 rounded-xl text-xs font-bold w-fit">
            <a href="{{ route('admin.partners.index', ['search' => request('search')]) }}" 
               class="px-4 py-2 rounded-lg transition {{ !request('status') ? 'bg-white text-indigo-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Semua
            </a>
            <a href="{{ route('admin.partners.index', ['status' => 'pending', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-lg transition flex items-center gap-1.5 {{ request('status') === 'pending' ? 'bg-white text-amber-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                Menunggu
            </a>
            <a href="{{ route('admin.partners.index', ['status' => 'approved', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-lg transition flex items-center gap-1.5 {{ request('status') === 'approved' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Disetujui
            </a>
            <a href="{{ route('admin.partners.index', ['status' => 'rejected', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-lg transition flex items-center gap-1.5 {{ request('status') === 'rejected' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                Ditolak
            </a>
        </div>

        <!-- Tombol Tambah Partner Manual -->
        <a href="{{ route('admin.partners.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            + Tambah Partner Baru
        </a>
    </div>
    
    <!-- Form Input Search Partner -->
    <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex flex-col sm:flex-row gap-3 justify-between items-center mb-4">
        <form action="{{ route('admin.partners.index') }}" method="GET" class="w-full sm:max-w-md relative">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" value="{{ $search ?? request('search') }}"
                   placeholder="Cari partner berdasarkan nama..."
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
            <div class="absolute left-3 top-3 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </form>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.partners.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">Reset Filter</a>
        @endif
    </div>

    <!-- Wadah Utama Tabel -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                        <th class="py-4 px-6 text-center w-16">NO</th>
                        <th class="py-4 px-6 w-24">LOGO</th>
                        <th class="py-4 px-6">NAMA ORGANISASI / PARTNER</th>
                        <th class="py-4 px-6">PEMILIK AKUN</th>
                        <th class="py-4 px-6 text-center w-32">STATUS</th>
                        <th class="py-4 px-6 w-36">TANGGAL DAFTAR</th>
                        <th class="py-4 px-6 text-center w-48">AKSI & PERSETUJUAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @forelse($partners as $index => $partner)
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- Nomor -->
                            <td class="py-4 px-6 text-center text-slate-400 font-normal">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Logo -->
                            <td class="py-4 px-6">
                                <div class="w-12 h-12 bg-slate-50 border border-slate-200 rounded-xl p-1 flex items-center justify-center overflow-hidden">
                                    @if($partner->logo)
                                        <img src="{{ asset('storage/' . $partner->logo) }}" class="max-w-full max-h-full object-contain rounded-lg" alt="Logo">
                                    @else
                                        <span class="font-bold text-indigo-600 text-sm">{{ strtoupper(substr($partner->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Nama Partner -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col max-w-xs">
                                    <span class="font-bold text-slate-900">{{ $partner->name }}</span>
                                    <span class="text-xs text-slate-400 font-normal truncate mt-0.5" title="{{ $partner->description }}">
                                        {{ Str::limit($partner->description, 35) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Pemilik Akun -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-800">{{ $partner->user->name ?? 'N/A' }}</span>
                                    <span class="text-xs text-slate-400 font-normal">{{ $partner->user->email ?? 'Tidak terhubung' }}</span>
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-6 text-center">
                                @if($partner->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                    </span>
                                @elseif($partner->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- Tanggal Dibuat -->
                            <td class="py-4 px-6 text-slate-500 text-xs font-normal">
                                {{ $partner->created_at ? $partner->created_at->format('d M Y, H:i') : '-' }}
                            </td>

                            <!-- Tombol Aksi & Persetujuan -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    {{-- Jika status PENDING: Tampilkan tombol Setujui & Tolak --}}
                                    @if($partner->status === 'pending')
                                        <form action="{{ route('admin.partners.approve', $partner->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Setujui pendaftaran partner {{ $partner->name }}?')"
                                                    class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.partners.reject', $partner->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Tolak pendaftaran partner {{ $partner->name }}?')"
                                                    class="px-2.5 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg text-xs font-bold transition">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        <!-- Tombol Edit biasa jika sudah diproses -->
                                        <a href="{{ route('admin.partners.edit', $partner->id) }}" class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endif

                                    <!-- Form Hapus -->
                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition" title="Hapus Data">
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
                            <td colspan="7" class="text-center py-12 text-slate-400 font-medium">
                                Belum ada data partner yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (jika data disajikan bertahap/paginate) -->
        @if(method_exists($partners, 'hasPages') && $partners->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection