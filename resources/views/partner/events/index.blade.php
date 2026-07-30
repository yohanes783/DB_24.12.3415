@extends('layouts.partner')

@section('page_title', 'Kelola Event Saya')
@section('page_subtitle', 'Daftar semua event yang diselenggarakan oleh ' . (auth()->user()->partner->name ?? 'organisasi kamu'))

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-slate-800">Daftar Event</h2>
    <a href="{{ route('partner.events.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
        <span>+</span> Tambah Event Baru
    </a>
</div>

{{-- Pesan Sukses jika Ada Notification --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold rounded-xl text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                    <th class="p-4">Poster</th>
                    <th class="p-4">Nama Event</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Tanggal & Waktu</th>
                    <th class="p-4">Harga Tiket</th>
                    <th class="p-4">Kuota</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($events as $event)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="p-4">
                        {{-- Menggunakan $event->poster_path dan fallback placeholder jika kosong --}}
                        <img src="{{ $event->poster_path ? asset('storage/' . $event->poster_path) : 'https://via.placeholder.com/150x100?text=No+Poster' }}" 
                             alt="{{ $event->title }}" 
                             class="w-16 h-12 object-cover rounded-lg border border-slate-200">
                    </td>
                    <td class="p-4 font-bold text-slate-800">{{ $event->title }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 font-semibold text-xs rounded-full">
                            {{ $event->category->name ?? 'Umum' }}
                        </span>
                    </td>
                    <td class="p-4 text-slate-600">
                        {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} <br>
                        <span class="text-xs text-slate-400">{{ $event->time }}</span>
                    </td>
                    <td class="p-4 font-bold text-slate-800">
                        {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                    </td>
                    <td class="p-4 font-semibold text-slate-700">
                        {{-- Menggunakan $event->stock (bukan $event->quota) --}}
                        {{ $event->stock ?? $event->quota ?? 0 }} Tiket
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('partner.events.edit', $event->id) }}" class="px-3 py-1.5 bg-amber-50 text-amber-600 font-bold rounded-lg hover:bg-amber-100 transition text-xs">
                                Edit
                            </a>
                            <form action="{{ route('partner.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus event ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 font-bold rounded-lg hover:bg-red-100 transition text-xs">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-slate-400">
                        Belum ada event yang kamu buat. Klik tombol <strong>+ Tambah Event Baru</strong> di atas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination jika data banyak --}}
    @if($events->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection